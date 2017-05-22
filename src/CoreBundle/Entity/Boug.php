<?php

namespace CoreBundle\Entity;

// use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Boug
 *
 * @ORM\Table(name="boug")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\BougRepository")
 */
class Boug extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
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
     * @var BougStoryReadAccess
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\BougStoryReadAccess", mappedBy="boug", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $storiesAccess;

    /**
     * @var BougStoryIsCharacter
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\BougStoryIsCharacter", mappedBy="boug", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $bougStoryIsCharacter;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Story", mappedBy="owner")
     * @ORM\JoinColumn(nullable=true)
     */
    private $stories;

    /**
    * @var array
    *
    * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Friends", mappedBy="boug1")
    * @ORM\JoinColumn(nullable=true)
    */
    private $friendsAdderOf;

    /**
    * @var array
    *
    * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Friends", mappedBy="boug2")
    * @ORM\JoinColumn(nullable=true)
    */
    private $friendsAddedBy;

    /**
    * @var array
    *
    * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\FriendsGroup", inversedBy="members")
    * @ORM\JoinColumn(nullable=true)
    */
    private $friendsGroups;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\FriendsGroup", mappedBy="manager")
     * @ORM\JoinColumn(nullable=true)
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
     * Add storiesAccess
     *
     * @param \CoreBundle\Entity\BougStoryReadAccess $storiesAccess
     *
     * @return Boug
     */
    public function addStoriesAccess(\CoreBundle\Entity\BougStoryReadAccess $storiesAccess)
    {
        $this->storiesAccess[] = $storiesAccess;

        return $this;
    }

    /**
     * Remove storiesAccess
     *
     * @param \CoreBundle\Entity\BougStoryReadAccess $storiesAccess
     */
    public function removeStoriesAccess(\CoreBundle\Entity\BougStoryReadAccess $storiesAccess)
    {
        $this->storiesAccess->removeElement($storiesAccess);
    }

    /**
     * Get storiesAccess
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStoriesAccess()
    {
        return $this->storiesAccess;
    }

    /**
     * Add bougStoryIsCharacter
     *
     * @param \CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter
     *
     * @return Boug
     */
    public function addBougStoryIsCharacter(\CoreBundle\Entity\BougStoryIsCharacter $bougStoryIsCharacter)
    {
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
     * Add story
     *
     * @param \CoreBundle\Entity\Story $story
     *
     * @return Boug
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
     * Add friendsAdderOf
     *
     * @param \CoreBundle\Entity\Friends $friendsAdderOf
     *
     * @return Boug
     */
    public function addFriendsAdderOf(\CoreBundle\Entity\Friends $friendsAdderOf)
    {
        $this->friendsAdderOf[] = $friendsAdderOf;

        return $this;
    }

    /**
     * Remove friendsAdderOf
     *
     * @param \CoreBundle\Entity\Friends $friendsAdderOf
     */
    public function removeFriendsAdderOf(\CoreBundle\Entity\Friends $friendsAdderOf)
    {
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
     * @param \CoreBundle\Entity\Friends $friendsAddedBy
     *
     * @return Boug
     */
    public function addFriendsAddedBy(\CoreBundle\Entity\Friends $friendsAddedBy)
    {
        $this->friendsAddedBy[] = $friendsAddedBy;

        return $this;
    }

    /**
     * Remove friendsAddedBy
     *
     * @param \CoreBundle\Entity\Friends $friendsAddedBy
     */
    public function removeFriendsAddedBy(\CoreBundle\Entity\Friends $friendsAddedBy)
    {
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
     * Add friendsGroup
     *
     * @param \CoreBundle\Entity\FriendsGroup $friendsGroup
     *
     * @return Boug
     */
    public function addFriendsGroup(\CoreBundle\Entity\FriendsGroup $friendsGroup)
    {
        $this->friendsGroups[] = $friendsGroup;

        return $this;
    }

    /**
     * Remove friendsGroup
     *
     * @param \CoreBundle\Entity\FriendsGroup $friendsGroup
     */
    public function removeFriendsGroup(\CoreBundle\Entity\FriendsGroup $friendsGroup)
    {
        $this->friendsGroups->removeElement($friendsGroup);
    }

    /**
     * Get friendsGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsGroups()
    {
        return $this->friendsGroups;
    }

    /**
     * Add groupsManaged
     *
     * @param \CoreBundle\Entity\FriendsGroup $groupsManaged
     *
     * @return Boug
     */
    public function addGroupsManaged(\CoreBundle\Entity\FriendsGroup $groupsManaged)
    {
        $this->groupsManaged[] = $groupsManaged;

        return $this;
    }

    /**
     * Remove groupsManaged
     *
     * @param \CoreBundle\Entity\FriendsGroup $groupsManaged
     */
    public function removeGroupsManaged(\CoreBundle\Entity\FriendsGroup $groupsManaged)
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
