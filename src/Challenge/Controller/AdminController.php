<?php
namespace Challenge\Controller;

use Profile\Entity\Utilisateur;
use Profile\Service\UtilisateurServiceInterface;
use Challenge\Entity\Concours;
use Challenge\Service\ConcoursServiceInterface;
use Challenge\Entity\Participation;
use Challenge\Service\ParticipationServiceInterface;

use Challenge\Entity\Vote;
use Challenge\Service\VoteServiceInterface;

use \DateTime;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Jol\User\Service\ReadOnlyUserService;

use Challenge\InputFilter\ConcoursFilter;
use Zend\Form\FormInterface;

use Doctrine\Common\Collections\Criteria;

use RuntimeException;

class AdminController extends AbstractActionController {
    /**
     * @var FormInterface
    */
    protected $concours_form;

    /**
     * @var ParticipationServiceInterface
    */
    protected $participationService;

    /**
     * @var ConcoursServiceInterface
    */
    protected $concoursService;

    /**
     * @var UtilisateurServiceInterface
    */
    protected $utilisateurService;

    /**
     * @var VoteServiceInterface;
    */ 
    protected $voteService;

    /**
     * @var ReadOnlyUserService 
    */
    protected $userRO;


    /**
     * @param FormInterface $concours_form
     * @param ParticipationServiceInterface $participationService
     * @param UtilisateurServiceInterface $utilisateurService
     * @param ConcoursServiceInterface $concoursService
     * @param VoteServiceInterface $voteService
     * @param ReadOnlyUserService $userRO
     * @param DateTime $dateTime
    */
    
    public function __construct(FormInterface $concours_form, ParticipationServiceInterface $participationService, ConcoursServiceInterface $concoursService, UtilisateurServiceInterface $utilisateurService, VoteServiceInterface $voteService, ReadOnlyUserService $userRO) {
        $this->concours_form = $concours_form;
        $this->participationService = $participationService;
        $this->concoursService = $concoursService;
        $this->utilisateurService = $utilisateurService;
        $this->voteService = $voteService;
        $this->userRO = $userRO;
    }

    public function indexAction() {
        $concours = $this->concoursService->getObjectManager()->createQuery("SELECT c FROM Challenge\Entity\Concours c order by c.fin_concours desc")->getResult();
        $view = new ViewModel();
        $view->setTemplate('challenge/admin/index');
        $view->setVariables([
            'concours' => $concours,
        ]);

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/admin/template_admin');
        $viewTemplate->addChild($view, 'content');
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }

    public function addConcoursAction() {
        $concoursForm = $this->concours_form;
        $concoursForm->setInputFilter(new ConcoursFilter());
        $validPost = 0;

        $request = $this->getRequest();
        if($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray()
            );
            $validPost = false;
            $concoursForm->setData($post);
            if($concoursForm->isValid()) {

                $debut_concours = DateTime::createFromFormat('d/m/Y H:i', $post['debut_concours'])->getTimeStamp();
                $fin_concours = DateTime::createFromFormat('d/m/Y H:i', $post['fin_concours'])->getTimeStamp();

                $newConcours = new Concours();
                $concoursForm->bind($newConcours);
                $newConcours->setGame($post['choix_jeu']);
                $newConcours->setTitreConcours($post['titre_concours']);
                $newConcours->setDescConcours($post['desc_concours']);
                $newConcours->setForumSubject($post['forum_sujet']);
                $newConcours->setDebutConcours($debut_concours);
                $newConcours->setFinConcours($fin_concours);
                $newConcours->setAllowUsers($post['allowed_users']);
                $newConcours->setShowParticipations($post['voir_participations']);

                $this->concoursService->save($newConcours);
                $validPost = 1;
            } else {$validPost = 0;}
        }

        $view = new ViewModel();
        $view->setTemplate('challenge/admin/addConcours');
        $view->setVariables([
            'validPost' => $validPost,
            'concours_form' => $concoursForm
        ]);

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/admin/template_admin');
        $viewTemplate->addChild($view, 'content');
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }

    public function editConcoursAction() {
        $concoursForm = $this->concours_form;
        $concoursForm->setInputFilter(new ConcoursFilter());
        $validPost = 0;

        $id = (int) $this->params()->fromRoute('id');

        $getConcours = $this->concoursService->find($id);
        if(!$getConcours) {
            return $this->redirect()->toRoute('challenge/admin');
        }

        $request = $this->getRequest();
        if($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray()
            );
            $concoursForm->setData($post);
            if($concoursForm->isValid()) {

                $debut_concours = DateTime::createFromFormat('d/m/Y H:i', $post['debut_concours'])->getTimeStamp();
                $fin_concours = DateTime::createFromFormat('d/m/Y H:i', $post['fin_concours'])->getTimeStamp();

                $getConcours->setGame($post['choix_jeu']);
                $getConcours->setTitreConcours($post['titre_concours']);
                $getConcours->setDescConcours($post['desc_concours']);
                $getConcours->setForumSubject($post['forum_sujet']);
                $getConcours->setDebutConcours($debut_concours);
                $getConcours->setFinConcours($fin_concours);
                $getConcours->setAllowUsers($post['allowed_users']);
                $getConcours->setShowParticipations($post['voir_participations']);

                $this->concoursService->save($getConcours);
                $validPost = 1;

            } else { $validPost = 0;}
        }

        $view = new ViewModel();
        $view->setTemplate('challenge/admin/editConcours');
        $view->setVariables([
            'validPost' => $validPost,
            'getConcours' => $getConcours,
            'concours_form' => $concoursForm
        ]);

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/admin/template_admin');
        $viewTemplate->addChild($view, 'content');
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }

    public function showConcoursAction() {

        $id = (int) $this->params()->fromRoute('id');

        $getConcours = $this->concoursService->find($id);
        if(!$getConcours) {
            return $this->redirect()->toRoute('challenge/admin');
        }

        // STATUT DU CONCOURS
        $status = 0;
        if(time() > $getConcours->getDebutConcours() && time() < $getConcours->getFinConcours()) {
            $status = 1; // En cours
        } else if (time() > ($getConcours->getFinconcours() + 604800)) {
            $status = 3; // Terminé
        } else if (time() > $getConcours->getFinConcours()) {
            $status = 2; // Vote du Jury
        } else {
            $status = 0; // A venir
        }

        $view = new ViewModel();
        $view->setTemplate('challenge/admin/showConcours');
        $view->setVariables([
            'concours' => $getConcours,
            'status' => $status,
            'userRO' => $this->userRO
        ]);

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/admin/template_admin');
        $viewTemplate->addChild($view, 'content');
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }

    public function voteConcoursAction() {

        $id = (int) $this->params()->fromRoute('id');
        $idJol = $this->user()->getId();

        $getConcours = $this->concoursService->find($id);
        if(!$getConcours) {
            return $this->redirect()->toRoute('challenge/admin');
        }
        // On récupère les utilisateurs autorisés à voter
        $allowed_users = explode(',', $getConcours->getAllowUsers());
        // Si l'utilisateur n'est pas dans la liste des utilisateurs autorisés et n'est pas membre de l'équipe JoL, ne lui permet pas l'accès
        if(!in_array($idJol, $allowed_users) && $this->user()->inJolGroup() == 0) {
            return $this->redirect()->toRoute('challenge');
        }

        // STATUT DU CONCOURS
        $status = 0;
        if(time() > $getConcours->getDebutConcours() && time() < $getConcours->getFinConcours()) {
            $status = 1; // En cours
        } else if (time() > ($getConcours->getFinconcours() + 604800)) {
            $status = 3; // Terminé
        } else if (time() > $getConcours->getFinConcours()) {
            $status = 2; // Vote du Jury
        } else {
            $status = 0; // A venir
        }
        // S'il est terminé, retour à l'accueil
        if($status != 2) {
            return $this->redirect()->toRoute('challenge/admin');
        }

        // Pour chaque participation, on recherche les votes déjà enregistrés
        $participations = array();
        foreach($getConcours->getParticipations() as $participation) {
            $getVotes = $participation->getVotes();
            // Si l'utilisateur a déjà voté pour une participation, on l'indique à l'utilisateur en lui laissant la possibilité de changer sa note
            $searchNote = $this->voteService->findOneBy(array('id_participation' => $participation->getIdParticipation(), 'id_votant' => $this->user()->getId()));
            if(!$searchNote) {
                $voteStatut = 0;
                $voteValue = '--';
            } else {
                $voteStatut = 1;
                $voteValue = $searchNote->getNote();
            }
            $participations[] = array($participation->getIdparticipation(), $participation->getParticipantId(), $participation->getDescParticipation(), $participation->getImgParticipation(), $voteStatut, $voteValue);
        }


        $view = new ViewModel();
        $view->setTemplate('challenge/admin/voteConcours');
        $view->setVariables([
            'concours' => $getConcours,
            'status' => $status,
            'participations' => $participations,
            'allowed_users' => $allowed_users,
            'userRO' => $this->userRO
        ]);

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/admin/template_admin');
        $viewTemplate->addChild($view, 'content');
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }

    public function resultConcoursAction() {

        $id = (int) $this->params()->fromRoute('id');
        $idJol = $this->user()->getId();

        $getConcours = $this->concoursService->find($id);
        if(!$getConcours) {
            return $this->redirect()->toRoute('challenge/admin');
        }
        if(!$idJol ||$idJol == 0) {
            return $this->redirect()->toRoute('challenge');
        }
        
        // STATUT DU CONCOURS
        $status = 0;
        if(time() > $getConcours->getDebutConcours() && time() < $getConcours->getFinConcours()) {
            $status = 1; // En cours
        } else if (time() > ($getConcours->getFinconcours() + 604800)) {
            $status = 3; // Terminé
        } else if (time() > $getConcours->getFinConcours()) {
            $status = 2; // Vote du Jury
        } else {
            $status = 0; // A venir
        }

        $participations = array();
        // On récupère toutes les participations du concours
        foreach($getConcours->getParticipations() as $participation) {
            // On récupère chaque vote donné à une participation et on en fait la moyenne des notes
            $nb_notes = 0;
            $addition = 0;
            foreach($participation->getVotes() as $note) {
                $nb_notes++;
                $addition += $note->getNote();
            }
            $resultat = $addition/$nb_notes;
            $resultat = number_format($resultat, 3, '.', ' ');
            $participations[] = array($participation->getIdParticipation(), $participation->getParticipantId(), $participation->getImgParticipation(),$participation->getDescParticipation(), $resultat);
        }

        $view = new ViewModel();
        $view->setTemplate('challenge/admin/resultConcours');
        $view->setVariables([
            'status' => $status,
            'userRO' => $this->userRO,
            'participations' => $participations,
            'concours' => $getConcours
        ]);

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/admin/template_admin');
        $viewTemplate->addChild($view, 'content');
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }
}
