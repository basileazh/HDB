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
}
