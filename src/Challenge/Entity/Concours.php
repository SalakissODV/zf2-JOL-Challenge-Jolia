<?php

namespace Challenge\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concours
 *
 * @ORM\Entity
 * @ORM\Table(name="challenge__concours")
 **/
class Concours {

	 /**
     * @var integer
     *
     * @ORM\Column(name="id_concours", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
     private $id_concours;

    /**
     * @var integer
     *
     * @ORM\Column(name="game", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $game;

     /**
     * @var string
     *
     * @ORM\Column(name="titre_concours", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */

     protected $titre_concours;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_concours", type="string", length=1000, precision=0, scale=0, nullable=false, unique=false)
     */

    protected $desc_concours;

    /**
     * @var string
     *
     * @ORM\Column(name="forum_subject", type="string", precision=0, scale=0, nullable=false, unique=false)
     */

    protected $forum_subject;



    /**
     * @var integer
     *
     * @ORM\Column(name="debut_concours", type="integer", length=11, precision=0, scale=0, nullable=false, unique=false)
     */

    protected $debut_concours;



    /**
     * @var integer
     *
     * @ORM\Column(name="fin_concours", type="integer", length=11, precision=0, scale=0, nullable=false, unique=false)
     */

    protected $fin_concours;

    /**
     * @var string
     *
     * @ORM\Column(name="allow_users", type="string", precision=0, scale=0, nullable=false, unique=false)
     */

    protected $allow_users;

     /**
     * @var string
     *
     * @ORM\Column(name="show_participations", type="string", precision=0, scale=0, nullable=false, unique=false)
     */

     protected $show_participations;

     /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Challenge\Entity\Participation", mappedBy="id_concours")
     */
     protected $participations;

    /**
     * Constructor
     */
    public function __construct() {
        $this->participations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get idConcours
     *
     * @return integer
     */

    public function getIdConcours() {
        return $this->id_concours;
    }

    /**
     * Get game
     *
     * @return integer
     */

    public function getGame() {
        return $this->game;
    }

    /**
     * Get titreConcours
     *
     * @return string
     */

    public function getTitreConcours() {
        return $this->titre_concours;
    }

    /**
     * Get descConcours
     *
     * @return string
     */

    public function getDescConcours() {
        return $this->desc_concours;
    }

    /**
     * Get forumSubject
     *
     * @return string
     */

    public function getForumSubject() {
        return $this->forum_subject;
    }

    /**
     * Get forumSubject
     *
     * @return string
     */

    public function getAllowUsers() {
        return $this->allow_users;
    }

    /**
     * Get forumSubject
     *
     * @return string
     */

    public function getShowParticipations() {
        return $this->show_participations;
    }

    /**
     * Get DebutConcours
     *
     * @return integer
     */

    public function getDebutConcours() {
        return $this->debut_concours;
    }

    /**
     * Get FinConcours
     *
     * @return integer
     */

    public function getFinConcours() {
        return $this->fin_concours;
    }

    /**
     * Set idConcours
     *
     * @param integer $id_concours
     *
     * @return Concours
     */

    public function setIdConcours($id_concours) {
        $this->id_concours = $id_concours;
        return $this;
    }

    /**
     * Set game
     *
     * @param integer $game
     *
     * @return Concours
     */

    public function setGame($game) {
        $this->game = $game;
        return $this;
    }

    /**
     * Set titreConcours
     *
     * @param string $titre_concours
     *
     * @return Concours
     */

    public function setTitreConcours($titre_concours) {
        $this->titre_concours = $titre_concours;
        return $this;
    }

    /**
     * Set descConcours
     *
     * @param string $desc_concours
     *
     * @return Concours
     */

    public function setDescConcours($desc_concours) {
        $this->desc_concours = $desc_concours;
        return $this;
    }

     /**
     * Set forumSubject
     *
     * @param string $forum_subject
     *
     * @return Concours
     */

     public function setForumSubject($forum_subject) {
        $this->forum_subject = $forum_subject;
        return $this;
    }

    /**
     * Set DebutConcours
     *
     * @param integer $debut_concours
     *
     * @return Concours
     */

    public function setDebutConcours($debut_concours) {
        $this->debut_concours = $debut_concours;
        return $this;
    }

    /**
     * Set FinConcours
     *
     * @param integer $fin_concours
     *
     * @return Concours
     */

    public function setFinConcours($fin_concours) {
        $this->fin_concours = $fin_concours;
        return $this;
    }

    /**
     * Set allowUsers
     *
     * @param string $allow_users
     *
     * @return Concours
     */

    public function setAllowUsers($allow_users) {
        $this->allow_users = $allow_users;
        return $this;
    }

    /**
     * Set showParticipations
     *
     * @param string $show_participations
     *
     * @return Concours
     */

    public function setShowParticipations($show_participations) {
        $this->show_participations = $show_participations;
        return $this;
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations() {
        return $this->participations;
    }

}