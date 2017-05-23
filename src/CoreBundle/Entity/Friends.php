<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="DateSince", type="datetime")
     */
    private $dateSince;

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
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Boug", mappedBy="friendsAddedBy")
     * @ORM\JoinColumn(nullable=false)
     */
    private $boug2;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateSince
     *
     * @param \DateTime $dateSince
     *
     * @return Friends
     */
    public function setDateSince($dateSince)
    {
        $this->dateSince = $dateSince;

        return $this;
    }

    /**
     * Get dateSince
     *
     * @return \DateTime
     */
    public function getDateSince()
    {
        return $this->dateSince;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->boug2 = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add boug2
     *
     * @param \CoreBundle\Entity\Boug $boug2
     *
     * @return Friends
     */
    public function addBoug2(\CoreBundle\Entity\Boug $boug2)
    {
        $this->boug2[] = $boug2;

        return $this;
    }

    /**
     * Remove boug2
     *
     * @param \CoreBundle\Entity\Boug $boug2
     */
    public function removeBoug2(\CoreBundle\Entity\Boug $boug2)
    {
        $this->boug2->removeElement($boug2);
    }

    /**
     * Get boug2
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBoug2()
    {
        return $this->boug2;
    }
}
