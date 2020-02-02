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


use Doctrine\Common\Collections\Criteria;

use RuntimeException;

class AjaxController extends AbstractActionController {   

    /**
     * @var AwardServiceInterface
    */
    protected $awardService;
    /**
     * @var AwardCategoryServiceInterface
    */
    protected $awardCategoryService;
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
     * @param AwardServiceInterface $awardService
     * @param AwardCategoryServiceInterface $awardCategoryService
     * @param UtilisateurServiceInterface $utilisateurService
     * @param VoteServiceInterface $voteService
     * @param ReadOnlyUserService $userRO
    */

    public function __construct(ParticipationServiceInterface $participationService, ConcoursServiceInterface $concoursService, UtilisateurServiceInterface $utilisateurService, VoteServiceInterface $voteService, ReadOnlyUserService $userRO) {
        $this->participationService = $participationService;
        $this->concoursService = $concoursService;
        $this->utilisateurService = $utilisateurService;
        $this->voteService = $voteService;
        $this->userRO = $userRO;
    }

    public function delConcoursAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if($this->getRequest()->isPost()) {
                $selectConcours = $this->getRequest()->getPost('Concours');
                $getConcours = $this->concoursService->find($selectConcours);
                if (!$getConcours) {
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('noConcours');
                } else {

                    // Procéder à la suppression logique des votes associées aux participations liées à ce concours, s'il y en a
                    $getVotes = $this->voteService->getObjectManager()->createQuery("SELECT v FROM Challenge\Entity\Vote v WHERE v.id_concours =".$selectConcours)->getResult();
                    if($getVotes != null) {
                        foreach($getVotes as $vote) {
                            $this->voteService->remove($vote);
                        }
                        $this->voteService->flush();
                    }
                    // Procéder à la suppression logique des participations liées à ce concours, s'il y en a
                    $getParticipations = $this->participationService->getObjectManager()->createQuery("SELECT p FROM Challenge\Entity\Participation p WHERE p.id_concours =".$selectConcours)->getResult();
                    if($getParticipations != null) {
                        foreach($getParticipations as $participation) {

                        	$file = str_replace('//jolstatic.fr/dofus/Modules/Challenge/upload/'.$getConcours->getIdConcours().'/', '', $participation->getImgParticipation());
                			$location = "/medias/Modules/Challenge/upload/".$getConcours->getIdConcours()."/".$file;

                        	unlink($location);
                            $this->participationService->remove($participation);
                        }
                        $this->participationService->flush();
                    }

                    // Procéder à la suppression du concours.
                    rmdir('/medias/Modules/Challenge/upload/'.$getConcours->getIdConcours());

                    $this->concoursService->remove($getConcours);
                    $this->concoursService->flush();

                    // On renvoi une réponse Ajax valide
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('ok');
                }

            } else {
                $headers = $this->getResponse()->getHeaders();
                $headers->addHeaderLine("Content-type: text/plain");
                return $this->getResponse()->setContent('erreur');
            }
        } else {
            return $this->redirect()->toRoute('challenge/admin');
        }

    }
    public function delParticipationAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if($this->getRequest()->isPost()) {
                $selectParticipation = $this->getRequest()->getPost('Participation');
                $getParticipation = $this->participationService->find($selectParticipation);
                if (!$getParticipation) {
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('noParticipation');
                } else {
                    // On supprime tous les éventuels votes associés à cette participation
                    foreach($getParticipation->getVotes() as $vote) {
                        $this->voteService->remove($vote);
                    }
                    $this->voteService->flush();

                    // On supprime la participation
                    $this->participationService->remove($getParticipation);
                    $this->participationService->flush();
                    
                    // On renvoi une réponse Ajax valide
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('ok');
                }

            } else {
                $headers = $this->getResponse()->getHeaders();
                $headers->addHeaderLine("Content-type: text/plain");
                return $this->getResponse()->setContent('erreur');
            }
        } else {
            return $this->redirect()->toRoute('challenge/admin');
        }

    }

    public function addVoteAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if($this->getRequest()->isPost()) {
                $participationId = $this->getRequest()->getPost('ParticipationId');
                $votantId = $this->getRequest()->getPost('VotantId');
                $noteValue = $this->getRequest()->getPost('NoteValue');
                $idConcours = $this->getRequest()->getPost('Concours');

                if($votantId == '0' || $votantId == '') {
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('erreur id votant');
                }
                if($noteValue == '--') {
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('erreur note');
                }
                if($idConcours == '0' || $idConcours == '') {
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('erreur concours');
                }
                $getParticipation = $this->participationService->find($participationId);
                // Si on ne trouve pas la participation, on annonce une erreur
                if (!$getParticipation) {
                    $headers = $this->getResponse()->getHeaders();
                    $headers->addHeaderLine("Content-type: text/plain");
                    return $this->getResponse()->setContent('noParticipation');
                } else { // Sinon on recherche si un vote existe déjà pour le votant actuel sur la participation souhaitée
                    $getVote = $this->voteService->findOneBy(array('id_participation' => $participationId, 'id_votant' => $votantId));
                    if(!$getVote) { // Si on en trouve pas, on ajoute le vote
                        $newVote = new Vote();
                        $newVote->setParticipation($getParticipation);
                        $newVote->setVotantId($votantId);
                        $newVote->setNote($noteValue);
                        $newVote->setConcoursId($idConcours);
                        $this->voteService->save($newVote);

                        // On renvoi une réponse Ajax valide
                        $headers = $this->getResponse()->getHeaders();
                        $headers->addHeaderLine("Content-type: text/plain");
                        return $this->getResponse()->setContent('ok');

                    } else { // Sinon on met à jour le vote précédent
                        $getVote->setNote($noteValue);
                        $this->voteService->save($getVote);

                        // On renvoi une réponse Ajax valide
                        $headers = $this->getResponse()->getHeaders();
                        $headers->addHeaderLine("Content-type: text/plain");
                        return $this->getResponse()->setContent('updateOK');
                    }
                }

            } else {
                $headers = $this->getResponse()->getHeaders();
                $headers->addHeaderLine("Content-type: text/plain");
                return $this->getResponse()->setContent('erreur');
            }
        } else {
            return $this->redirect()->toRoute('challenge/admin');
        }
    }
}