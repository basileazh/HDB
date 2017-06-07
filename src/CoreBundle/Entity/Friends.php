<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Friends
 *
 * @ORM\Table(name="friends")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\FriendsRepository")
 */
class Friends
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateSinceAgreement", type="datetime", nullable=true)
     */
    private $dateSinceAgreement = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateSinceDemand", type="datetime")
     */
    private $dateSinceDemand;

    /**
     * @var Boug
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Boug", inversedBy="friendsAdderOf")
     * @ORM\JoinColumn(nullable=false)
     */
    private $boug1;

    /**
     * @var Boug
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Boug", inversedBy="friendsAddedBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $boug2;

    /**
     * @var \Bool
     *
     * @ORM\Column(name="waitingForAnswer", type="boolean")
     * @ORM\JoinColumn(nullable=false)
     */
    private $waitingForAnswer;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->waitingForAnswer = true;
        $this->dateSinceDemand = new \Datetime();
        $this->dateSinceAgreement = null;
        $this->boug2 = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    

    /**
     * Set dateSinceDemand
     *
     * @param \DateTime $dateSinceDemand
     *
     * @return Friends
     */
    public function setDateSinceDemand($dateSinceDemand)
    {
        $this->dateSinceDemand = $dateSinceDemand;

        return $this;
    }

    /**
     * Get dateSinceDemand
     *
     * @return \DateTime
     */
    public function getDateSinceDemand()
    {
        return $this->dateSinceDemand;
    }

    /**
     * Set waitingForAnswer
     *
     * @param boolean $waitingForAnswer
     *
     * @return Friends
     */
    public function setWaitingForAnswer($waitingForAnswer)
    {
        $this->waitingForAnswer = $waitingForAnswer;

        return $this;
    }

    /**
     * Get waitingForAnswer
     *
     * @return boolean
     */
    public function getWaitingForAnswer()
    {
        return $this->waitingForAnswer;
    }

    /**
     * Set boug1
     *
     * @param \CoreBundle\Entity\Boug $boug1
     *
     * @return Friends
     */
    public function setBoug1(\CoreBundle\Entity\Boug $boug1)
    {
        $this->boug1 = $boug1;

        return $this;
    }

    /**
     * Get boug1
     *
     * @return \CoreBundle\Entity\Boug
     */
    public function getBoug1()
    {
        return $this->boug1;
    }

    /**
     * Set boug2
     *
     * @param \CoreBundle\Entity\Boug $boug2
     *
     * @return Friends
     */
    public function setBoug2(\CoreBundle\Entity\Boug $boug2)
    {
        $this->boug2 = $boug2;

        return $this;
    }

    /**
     * Get boug2
     *
     * @return \CoreBundle\Entity\Boug
     */
    public function getBoug2()
    {
        return $this->boug2;
    }

    /**
     * Set dateSinceAgreement
     *
     * @param \DateTime $dateSinceAgreement
     *
     * @return Friends
     */
    public function setDateSinceAgreement($dateSinceAgreement)
    {
        $this->dateSinceAgreement = $dateSinceAgreement;

        return $this;
    }

    /**
     * Get dateSinceAgreement
     *
     * @return \DateTime
     */
    public function getDateSinceAgreement()
    {
        return $this->dateSinceAgreement;
    }
}
