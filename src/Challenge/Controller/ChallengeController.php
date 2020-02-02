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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Jol\User\Service\ReadOnlyUserService;

use Challenge\InputFilter\ParticipationFilter;
use Zend\Form\FormInterface;

use Doctrine\Common\Collections\Criteria;

use RuntimeException;

class ChallengeController extends AbstractActionController {

    /**
     * @var FormInterface
    */
    protected $form_participation;

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
     * @var VoteServiceInterface
    */
    protected $voteService;

    /**
     * @var ReadOnlyUserService 
    */
    protected $userRO;

    /**
     * @param FormInterface $form_participation
     * @param ParticipationServiceInterface $participationService
     * @param UtilisateurServiceInterface $utilisateurService
     * @param ConcoursServiceInterface $concoursService
     * @param VoteserviceInterface $voteService;
     * @param ReadOnlyUserService $userRO
    */
    
    public function __construct(FormInterface $form_participation, ParticipationServiceInterface $participationService, ConcoursServiceInterface $concoursService, UtilisateurServiceInterface $utilisateurService, VoteServiceInterface $voteService, ReadOnlyUserService $userRO) {
    	$this->form_participation = $form_participation;
    	$this->participationService = $participationService;
    	$this->concoursService = $concoursService;
    	$this->utilisateurService = $utilisateurService;
    	$this->voteService = $voteService;
    	$this->userRO = $userRO;
    }
    public function indexAction() {


    	$view = new ViewModel();
    	$view->setTemplate('challenge/index');


    	$viewTemplate = new ViewModel();
    	$viewTemplate->setTemplate('challenge/template_challenge');

    	$viewTemplate->addChild($view, 'content');

    	$viewTemplate->setVariables([
    	]);

    	return $viewTemplate;

    }

    public function listConcoursAction() {

    	$idJol = $this->user()->getId();

    	if(!$idJol) {
    		$verifUser = 0;
    	} else { 
    		$verifUser = 1; 
    	}

    	$timeNow = time();

    	$getConcours = $this->concoursService->getObjectManager()->createQuery('select c from Challenge\Entity\Concours c')->getResult();

    	$view = new ViewModel();
    	$view->setTemplate('challenge/listConcours');
    	$view->setVariables([
    		'isConnected' => $verifUser,
    		'getConcours' => $getConcours
    	]);

    	$viewTemplate = new ViewModel();
    	$viewTemplate->setTemplate('challenge/template_challenge');

    	$viewTemplate->addChild($view, 'content');
    	$viewTemplate->setVariables([]);

    	return $viewTemplate;

    }

    public function helpAction() {

    	$view = new ViewModel();
    	$view->setTemplate('challenge/help');


    	$viewTemplate = new ViewModel();
    	$viewTemplate->setTemplate('challenge/template_challenge');

    	$viewTemplate->addChild($view, 'content');


    	$viewTemplate->setVariables([]);

    	return $viewTemplate;
    }

    public function participerAction() {

    	$idJol = $this->user()->getId();
    	$request = $this->getRequest();

    	if(!$idJol) {
    		return $this->redirect()->toRoute('challenge');
    	}

    	$utilisateur = $this->utilisateurService->find($idJol);
    	if(!$utilisateur ) {
    		$Profile = new Utilisateur();
    		$Profile->setIdJol($idJol);
    		$Profile->setShowInfos(1);
    		$this->utilisateurService->save($Profile);
    		$utilisateur = $this->utilisateurService->find($idJol);
    	} 


    	$id = (int) $this->params()->fromRoute('id');
    	if(!$id) {
    		return $this->redirect()->toRoute('challenge');
    	}

    	$concours = $this->concoursService->find($id);
    	if (!$concours) {
    		return $this->redirect()->toRoute('challenge');
    	}
    	$participations = $concours->getParticipations();

        // STATUT DU CONCOURS
    	$status = 0;
    	if(time() > $concours->getDebutConcours() && time() < $concours->getFinConcours()) {
            $status = 1; // En cours
        } else if (time() > ($concours->getFinconcours() + 604800)) {
            $status = 3; // Terminé
        } else if (time() > $concours->getFinConcours()) {
            $status = 2; // Vote du Jury
        } else {
            $status = 0; // Avenir
        }
        // S'il est terminé, retour à l'accueil
        if($status != 1) {
        	return $this->redirect()->toRoute('challenge');
        }

        
        $form_participation = $this->form_participation;
        $form_participation->setInputFilter(new ParticipationFilter());
        $validPost = 0;
        if($request->isPost()) {


        	$post = array_merge_recursive(
        		$request->getPost()->toArray(),
        		$request->getFiles()->toArray()
        	);

        	$form_participation->setData($post);
        	if($form_participation->isValid()) {
        		$validPost = 1;
                // Dossier de destination du fichier
        		$location = "/medias/Modules/Challenge/upload/".$concours->getIdConcours();
                // S'il n'existe pas, on le créé
        		if(!is_dir($location)) {
        			mkdir($location, 0777);
        			chmod($location, 0777);
        		}
                 // Extensions de fichiers autorisées
        		$allowedExtension = array('jpg', 'jpeg', 'png');

        		$extension = explode('.', $post['image_p']['name']);
        		$extension = strtolower(end($extension));
        		$fileName = $idJol.'_'.time().'.'.$extension;
        		$fileName = str_replace(array("'", '"', '`'), "", $fileName);

                // On vérifie que tout est ok
        		if (0 === $post['image_p']['error'] && in_array($extension, $allowedExtension)) {
                    // On balance le fichier dans le dossier d'upload du Module
        			move_uploaded_file($post['image_p']['tmp_name'], $location.'/'.$fileName);
        			$participationDesc = str_replace(array(".", "."), "¤", $post['desc_participation']);

                    // On enregistre les informations dans la base de données
        			$participation = new Participation();
        			$form_participation->bind($participation);
        			$participation->setConcours($concours);
        			$participation->setParticipantId($idJol);
        			$participation->setDescParticipation(strip_tags($participationDesc));
        			$participation->setImgParticipation('//jolstatic.fr/dofus/Modules/Challenge/upload/'.$concours->getIdConcours().'/'.$fileName);
        			$this->participationService->save($participation);
        		} else {
        			$validPost = 0;
        		}
        	}
        }

        $verifParticipation = $this->participationService->getObjectManager()->createQuery('SELECT p FROM Challenge\Entity\Participation p WHERE p.id_participant = '.$idJol.' AND p.id_concours ='.$concours->getIdConcours())->getResult();
        // Si on trouve un résultat
        if($verifParticipation) {
        	$participationResult = array($verifParticipation[0]->getDescParticipation(), $verifParticipation[0]->getImgParticipation());
        } else {
        	$participationResult = 0;
        }

        $view = new ViewModel();
        $view->setTemplate('challenge/participer');

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/template_challenge');

        $viewTemplate->addChild($view, 'content');

        $view->setVariables([
        	'validPost' => $validPost,
        	'participation_exist' => $participationResult,
        	'form_participation' => $form_participation,
        	'concours' => $concours,
        	'participations' => $participations,
        	'userRO' => $this->userRO,
        	'utilisateur' => $utilisateur,
        	'status' => $status
        ]);
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }

    public function voirAction() {
    	$idJol = $this->user()->getId();
    	$request = $this->getRequest();

    	$id = (int) $this->params()->fromRoute('id');
    	if(!$id) {
    		return $this->redirect()->toRoute('challenge');
    	}

    	$concours = $this->concoursService->find($id);
    	if (!$concours) {
    		return $this->redirect()->toRoute('challenge');
    	}
    	$participations = $concours->getParticipations();

        // STATUT DU CONCOURS
    	$status = 0;
    	if(time() > $concours->getDebutConcours() && time() < $concours->getFinConcours()) {
            $status = 1; // En cours
        } else if (time() > ($concours->getFinconcours() + 604800)) {
            $status = 3; // Terminé
        } else if (time() > $concours->getFinConcours()) {
            $status = 2; // Vote du Jury
        } else {
            $status = 0; // A venir
        }
        // S'il n'est pas terminé ou en cours de votes, retour à l'accueil
        if($status != 2 && $status != 3) {
        	return $this->redirect()->toRoute('challenge');
        }
        $participations_podium = null;
        // Si terminé
        if($status == 3) {
        	$participations_podium = array();
        // Détermination du top 3
        	foreach($concours->getParticipations() as $participation) {
        		//$list_participations = array();
        		$nb_notes = 0;
        		$addition = 0;
        		$i_participation = 0;

        		$listNotes = array();
        		foreach($participation->getVotes() as $note) {
        			
        			$nb_notes++;
        			$addition += $note->getNote();
        			$listNotes[] = $note->getNote();
        		}
        		$resultat = $addition / $nb_notes;
        		$resultat = number_format($resultat, 3, '.', ' ');
        		$participations_podium[] = array('id' => $participation->getIdParticipation(), 'userid' => $participation->getParticipantId(), 'user' => $this->userRO->find($participation->getParticipantId())->getPseudonym(), 'img' => $participation->getImgParticipation(), 'desc' => $participation->getDescParticipation(), 'result' => $resultat, 'notes' => $listNotes);
        	}

        	$sort = array();
        	foreach($participations_podium as $k=>$v) {
        		$sort['result'][$k] = $v['result'];
        	}
        	// Tri par résultat
        	$podium = array_multisort($sort['result'], SORT_DESC, $participations_podium);
        }
        

        $view = new ViewModel();
        if($this->user()->getId() == 412805) {
        	$view->setTemplate('challenge/voir_sala');
        } else {
        	$view->setTemplate('challenge/voir');
        }
        

        $viewTemplate = new ViewModel();
        $viewTemplate->setTemplate('challenge/template_challenge');

        $viewTemplate->addChild($view, 'content');

        $view->setVariables([
        	'concours' => $concours,
        	'userRO' => $this->userRO,
        	'podium' => $participations_podium,
        	'status' => $status
        ]);
        $viewTemplate->setVariables([]);

        return $viewTemplate;
    }   
}
