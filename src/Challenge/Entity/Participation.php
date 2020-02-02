<?php

namespace Challenge\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Entity
 * @ORM\Table(name="challenge__participations")
 **/
class Participation {

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
     * @ORM\ManyToOne(targetEntity="Challenge\Entity\Concours", inversedBy="participations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_concours", referencedColumnName="id_concours", nullable=false)
     * })
     */
    private $id_concours;


     /**
     * @var integer
     *
     * @ORM\Column(name="id_participant", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */

    protected $id_participant;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_participation", type="string", length=500, precision=0, scale=0, nullable=false, unique=false)
     */

    protected $desc_participation;

    /**
     * @var string
     *
     * @ORM\Column(name="img_participation", type="string", precision=0, scale=0, nullable=false, unique=false)
     */

    protected $img_participation;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Challenge\Entity\Vote", mappedBy="id_participation")
     */
     protected $votes;

    /**
     * Constructor
     */
    public function __construct() {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get idParticipation
     *
     * @return integer
     */

    public function getIdParticipation() {
        return $this->id;
    }

     /**
     * Set participationId
     *
     * @param integer $id
     *
     * @return Concours
     */

    public function setIdParticipation($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get concours
     *
     * @return \Challenge\Entity\Concours
     */

    public function getConcours() {
        return $this->id_concours;
    }

    /**
     * Set awardCategory
     *
     * @param \Challenge\Entity\Concours $id_concours
     *
     * @return Participation
     */

    public function setConcours(\Challenge\Entity\Concours $id_concours = null) {
        $this->id_concours = $id_concours;
        return $this;
    }

    /**
     * Get participantId
     *
     * @return integer
     */

    public function getParticipantId() {
        return $this->id_participant;
    }

    /**
     * Set participantId
     *
     * @param integer $id_participant
     *
     * @return Concours
     */

    public function setParticipantId($id_participant) {
        $this->id_participant = $id_participant;
        return $this;
    }

    /**
     * Get descConcours
     *
     * @return string
     */

    public function getDescParticipation() {
        return $this->desc_participation;
    }

    /**
     * Set descParticipation
     *
     * @param string $desc_participation
     *
     * @return Concours
     */

    public function setDescParticipation($desc_participation) {
        $this->desc_participation = $desc_participation;
        return $this;
    }

    /**
     * Get imgParticipation
     *
     * @return string
     */

    public function getImgParticipation() {
        return $this->img_participation;
    }

    /**
     * Set imgParticipation
     *
     * @param string $img_participation
     *
     * @return Concours
     */

    public function setImgParticipation($img_participation) {
        $this->img_participation = $img_participation;
        return $this;
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes() {
        return $this->votes;
    }
}