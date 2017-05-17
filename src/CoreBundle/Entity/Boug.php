<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Boug
 *
 * @ORM\Table(name="boug")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\BougRepository")
 */
class Boug
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
     * @var string
     *
     * @ORM\Column(name="FirstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="Login", type="string", length=255, unique=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateRegistration", type="datetime")
     */
    private $dateRegistration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateBirth", type="datetime")
     */
    private $dateBirth;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsAdmin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var bool
     *
     * @ORM\Column(name="IsActive", type="boolean")
     */
    private $isActive;

    /**
     * @var BougStoryReadAccess
     *
     * @ORM\OneToMany(targetEntity="HDB\CoreBundle\Entity\BougStoryReadAccess", mappedBy="boug")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bougStoryReadAccess;

    /**
     * @var BougStoryIsCharacter
     *
     * @ORM\OneToMany(targetEntity="HDB\CoreBundle\Entity\BougStoryIsCharacter", mappedBy="boug")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bougStoryIsCharacter;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="HDB\CoreBundle\Entity\Story", mappedBy="owner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stories;

    /**
    * @var array
    *
    * @ORM\OneToMany(targetEntity="HDB\CoreBundle\Entity\Friends", mappedBy="boug1")
    */
    private $friendsAdderOf;

    /**
    * @var array
    *
    * @ORM\OneToMany(targetEntity="HDB\CoreBundle\Entity\Friends", mappedBy="boug2")
    */
    private $friendsAddedBy;

    /**
    * @var array
    *
    * @ORM\ManyToMany(targetEntity="HDB\CoreBundle\Entity\FriendsGroup", inversedBy="members")
    */
    private $groups;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="HDB\CoreBundle\Entity\FriendsGroup", mappedBy="manager")
     */
    private $groupsManaged;

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
     * @return Boug
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Boug
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Boug
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Boug
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateRegistration
     *
     * @param \DateTime $dateRegistration
     *
     * @return Boug
     */
    public function setDateRegistration($dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;

        return $this;
    }

    /**
     * Get dateRegistration
     *
     * @return \DateTime
     */
    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    /**
     * Set dateBirth
     *
     * @param \DateTime $dateBirth
     *
     * @return Boug
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return \DateTime
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     *
     * @return Boug
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Boug
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bougStoryReadAccess = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bougStoryReadAccess
     *
     * @param \HDB\CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess
     *
     * @return Boug
     */
    public function addBougStoryReadAccess(\HDB\CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess)
    {
        $bougStoryReadAccess->setBoug($this);
        $this->bougStoryReadAccess[] = $bougStoryReadAccess;

        return $this;
    }

    /**
     * Remove bougStoryReadAccess
     *
     * @param \HDB\CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess
     */
    public function removeBougStoryReadAccess(\HDB\CoreBundle\Entity\BougStoryReadAccess $bougStoryReadAccess)
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
     * @param \HDB\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter
     *
     * @return Boug
     */
    public function addBougStoryIsCharacter(\HDB\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter)
    {
        $bougStoryIsCharacter->setBoug($this);
        $this->bougStoryIsCharacter[] = $bougStoryIsCharacter;

        return $this;
    }

    /**
     * Remove bougStoryIsCharacter
     *
     * @param \HDB\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter
     */
    public function removeBougStoryIsCharacter(\HDB\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter)
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
     * Add story
     *
     * @param \HDB\CoreBundle\Entity\Story $story
     *
     * @return Boug
     */
    public function addStory(\HDB\CoreBundle\Entity\Story $story)
    {
        $story->setOwner($this);
        $this->stories[] = $story;

        return $this;
    }

    /**
     * Remove story
     *
     * @param \HDB\CoreBundle\Entity\Story $story
     */
    public function removeStory(\HDB\CoreBundle\Entity\Story $story)
    {
        $story->setOwner(null);
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
     * Add friendsAdderOf
     *
     * @param \HDB\CoreBundle\Entity\Friends $friendsAdderOf
     *
     * @return Boug
     */
    public function addFriendsAdderOf(\HDB\CoreBundle\Entity\Friends $friendsAdderOf)
    {
        $friendsAdderOf->setBoug1($this);
        $this->friendsAdderOf[] = $friendsAdderOf;

        return $this;
    }

    /**
     * Remove friendsAdderOf
     *
     * @param \HDB\CoreBundle\Entity\Friends $friendsAdderOf
     */
    public function removeFriendsAdderOf(\HDB\CoreBundle\Entity\Friends $friendsAdderOf)
    {
        $friendsAdderOf->setBoug1(null);
        $this->friendsAdderOf->removeElement($friendsAdderOf);
    }

    /**
     * Get friendsAdderOf
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsAdderOf()
    {
        return $this->friendsAdderOf;
    }

    /**
     * Add friendsAddedBy
     *
     * @param \HDB\CoreBundle\Entity\Friends $friendsAddedBy
     *
     * @return Boug
     */
    public function addFriendsAddedBy(\HDB\CoreBundle\Entity\Friends $friendsAddedBy)
    {
        $friendsAddedBy->setBoug2($this);
        $this->friendsAddedBy[] = $friendsAddedBy;

        return $this;
    }

    /**
     * Remove friendsAddedBy
     *
     * @param \HDB\CoreBundle\Entity\Friends $friendsAddedBy
     */
    public function removeFriendsAddedBy(\HDB\CoreBundle\Entity\Friends $friendsAddedBy)
    {
        $friendsAddedBy->setBoug2(null);
        $this->friendsAddedBy->removeElement($friendsAddedBy);
    }

    /**
     * Get friendsAddedBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsAddedBy()
    {
        return $this->friendsAddedBy;
    }

    /**
     * Add group
     *
     * @param \HDB\CoreBundle\Entity\FriendsGroup $group
     *
     * @return Boug
     */
    public function addGroup(\HDB\CoreBundle\Entity\FriendsGroup $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \HDB\CoreBundle\Entity\FriendsGroup $group
     */
    public function removeGroup(\HDB\CoreBundle\Entity\FriendsGroup $group)
    {
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

    /**
     * Add groupsManaged
     *
     * @param \HDB\CoreBundle\Entity\FriendsGroup $groupsManaged
     *
     * @return Boug
     */
    public function addGroupsManaged(\HDB\CoreBundle\Entity\FriendsGroup $groupsManaged)
    {
        $groupsManaged->setManager($this);
        $this->groupsManaged[] = $groupsManaged;

        return $this;
    }

    /**
     * Remove groupsManaged
     *
     * @param \HDB\CoreBundle\Entity\FriendsGroup $groupsManaged
     */
    public function removeGroupsManaged(\HDB\CoreBundle\Entity\FriendsGroup $groupsManaged)
    {
        $this->groupsManaged->removeElement($groupsManaged);
    }

    /**
     * Get groupsManaged
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupsManaged()
    {
        return $this->groupsManaged;
    }
}