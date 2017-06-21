<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FriendsGroup
 *
 * @ORM\Table(name="friends_group")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\FriendsGroupRepository")
 */
class FriendsGroup
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
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateCreation", type="date")
     * @Gedmo\Timestampable(on="create")
     */
    private $dateCreation;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Story", mappedBy="groups", cascade={"persist"})
     */
    private $stories;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Boug", mappedBy="friendsGroups", cascade={"persist"})
     */
    private $members;

    /**
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Boug", inversedBy="groupsManaged", cascade={"persist"})
     */
    private $manager;


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
     * Set name
     *
     * @param string $name
     *
     * @return FriendsGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return FriendsGroup
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add story
     *
     * @param \CoreBundle\Entity\Story $story
     *
     * @return FriendsGroup
     */
    public function addStory(\CoreBundle\Entity\Story $story)
    {
        $this->stories[] = $story;

        return $this;
    }

    /**
     * Remove story
     *
     * @param \CoreBundle\Entity\Story $story
     */
    public function removeStory(\CoreBundle\Entity\Story $story)
    {
        $this->stories->removeElement($story);
    }

    /**
     * Get stories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStories()
    {
        return $this->stories;
    }

    /**
     * Add member
     *
     * @param \CoreBundle\Entity\Boug $member
     *
     * @return FriendsGroup
     */
    public function addMember(\CoreBundle\Entity\Boug $member)
    {
        $member->addFriendsGroup($this);
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \CoreBundle\Entity\Boug $member
     */
    public function removeMember(\CoreBundle\Entity\Boug $member)
    {
        $group->removeGroup($this);
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set manager
     *
     * @param \CoreBundle\Entity\Boug $manager
     *
     * @return FriendsGroup
     */
    public function setManager(\CoreBundle\Entity\Boug $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \CoreBundle\Entity\Boug
     */
    public function getManager()
    {
        return $this->manager;
    }
}
