<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Story
 *
 * @ORM\Table(name="story")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\StoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Story
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
     * @Assert\NotBlank() 
     * @Assert\Type("string",message="The value {{ value }} is not a valid {{ type }}.") 
     *
     * @ORM\Column(name="Title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank() 
     * @Assert\Type("string",message="The value {{ value }} is not a valid {{ type }}.") 
     *
     *
     * @ORM\Column(name="Content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="DateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateLastModification", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $dateLastModification;

    /**
     * @var BougStoryReadAccess
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\BougStoryReadAccess", mappedBy="story", cascade={"persist", "remove"})
     */
    private $bougStoryReadAccess;
    //* @ORM\JoinColumn(nullable=false)

    /**
     * @var BougStoryIsCharacter
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\BougStoryIsCharacter", mappedBy="story", cascade={"persist", "remove"})
     */
    private $bougStoryIsCharacter;
     //* @ORM\JoinColumn(nullable=false)

    /**
     * @var Boug
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Boug", inversedBy="stories", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\FriendsGroup", inversedBy="stories", cascade={"persist"})
     */
    private $groups;

    /**
     * @var bool
     *
     * @ORM\Column(name="isPublic", type="boolean")
     */
    private $isPublic;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bougStoryReadAccess = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bougStoryIsCharacter = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Update $dateLastModification flield
     *
     * @ORM\PrePersist
     */    
    public function updateDateLastModification()
    {
        $this->setDateLastModification(new \Datetime());
    }

    // Compare deux Stories selon leur date de création. Renvoie 0 si égal, 1 si $s1 est plus récente, -1 si $s2 est plus récente
    public static function compareStoriesByCreationDate($s1, $s2)
    {
        if ($s1->getDateCreation() == $s1->getDateCreation())
            return 0;

        return ($s1->getDateCreation() > $s2->getDateCreation()) ? +1 : -1;
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
     * Set title
     *
     * @param string $title
     *
     * @return Story
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Story
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Story
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
     * Set dateLastModification
     *
     * @param \DateTime $dateLastModification
     *
     * @return Story
     */
    public function setDateLastModification($dateLastModification)
    {
        $this->dateLastModification = $dateLastModification;

        return $this;
    }

    /**
     * Get dateLastModification
     *
     * @return \DateTime
     */
    public function getDateLastModification()
    {
        return $this->dateLastModification;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     *
     * @return Story
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Add bougStoryReadAccess
     *
     * @param \CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess
     *
     * @return Story
     */
    public function addBougStoryReadAccess(\CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess)
    {
        $bougStoryReadAccess->setStory($this);
        $this->bougStoryReadAccess[] = $bougStoryReadAccess;

        return $this;
    }

    /**
     * Remove bougStoryReadAccess
     *
     * @param \CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess
     */
    public function removeBougStoryReadAccess(\CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess)
    {
        $this->bougStoryReadAccess->removeElement($bougStoryReadAccess);
    }

    /**
     * Get bougStoryReadAccess
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBougStoryReadAccess()
    {
        return $this->bougStoryReadAccess;
    }

    /**
     * Add bougStoryIsCharacter
     *
     * @param \CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter
     *
     * @return Story
     */
    public function addBougStoryIsCharacter(\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter)
    {
        $bougStoryIsCharacter->setStory($this);
        $this->bougStoryIsCharacter[] = $bougStoryIsCharacter;

        return $this;
    }

    /**
     * Remove bougStoryIsCharacter
     *
     * @param \CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter
     */
    public function removeBougStoryIsCharacter(\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter)
    {
        $this->bougStoryIsCharacter->removeElement($bougStoryIsCharacter);
    }

    /**
     * Get bougStoryIsCharacter
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBougStoryIsCharacter()
    {
        return $this->bougStoryIsCharacter;
    }

    /**
     * Set owner
     *
     * @param \CoreBundle\Entity\Boug $owner
     *
     * @return Story
     */
    public function setOwner(\CoreBundle\Entity\Boug $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \CoreBundle\Entity\Boug
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add group
     *
     * @param \CoreBundle\Entity\FriendsGroup $group
     *
     * @return Story
     */
    public function addGroup(\CoreBundle\Entity\FriendsGroup $group)
    {
        $group->addStory($this);
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \CoreBundle\Entity\FriendsGroup $group
     */
    public function removeGroup(\CoreBundle\Entity\FriendsGroup $group)
    {
        $group->removeStory($this);
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
