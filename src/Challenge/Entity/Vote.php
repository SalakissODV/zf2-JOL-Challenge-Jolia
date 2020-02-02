<?php

namespace Challenge\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Entity
 * @ORM\Table(name="challenge__votes")
 **/
class Vote {

	 /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


     /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Challenge\Entity\Participation", inversedBy="votes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_participation", referencedColumnName="id", nullable=false)
     * })
     */

    protected $id_participation;

    /**
     * @var string
     *
     * @ORM\Column(name="id_concours", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */

    protected $id_concours;

    /**
     * @var string
     *
     * @ORM\Column(name="id_votant", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */

    protected $id_votant;

    /**
     * @var string
     *
     * @ORM\Column(name="votant_note", type="decimal", precision=2, scale=1, nullable=false, unique=false)
     */

    protected $note;



    /**

     * Constructor

     */

    /**
     * Get idParticipation
     *
     * @return integer
     */

    public function getIdVote() {
        return $this->id;
    }

     /**
     * Set participationId
     *
     * @param integer $id
     *
     * @return Vote
     */

    public function setIdVote($id) {
        $this->id = $id;
        return $this;
    }


    /**
     * Get concours
     *
     * @return \Challenge\Entity\Participation
     */

    public function getParticipation() {
        return $this->id_participation;
    }

    /**
     * Set participation
     *
     * @param \Challenge\Entity\Participation $id_participation
     *
     * @return Participation
     */

    public function setParticipation(\Challenge\Entity\Participation $id_participation = null) {
        $this->id_participation = $id_participation;
        return $this;
    }

   /**
     * Get id_votant
     *
     * @return integer
     */

    public function getVotantId() {
        return $this->id_votant;
    }

    /**
     * Set id_votant
     *
     * @param integer $id_votant
     *
     * @return Vote
     */

    public function setVotantId($id_votant) {
        $this->id_votant = $id_votant;
        return $this;
    }



    /**
     * Get note
     *
     * @return integer
     */

    public function getNote() {
        return $this->note;
    }

     /**
     * Set note
     *
     * @param integer $note
     *
     * @return Vote
     */

    public function setNote($note) {
        $this->note = $note;
        return $this;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Vote
     */

    public function setParticipantId($note) {
        $this->note = $note;
        return $this;
    }

    /**
     * Get id_concours
     *
     * @return integer
     */

    public function getConcoursId() {
        return $this->id_concours;
    }

    /**
     * Set id_concours
     *
     * @param integer $id_concours
     *
     * @return Vote
     */

    public function setConcoursId($id_concours) {
        $this->id_concours = $id_concours;
        return $this;
    }
}