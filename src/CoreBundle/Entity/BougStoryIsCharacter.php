<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BougStoryIsCharacter
 *
 * @ORM\Table(name="boug_story_is_character")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\BougStoryIsCharacterRepository")
 */
class BougStoryIsCharacter
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
     * @var Boug
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Boug", inversedBy="bougStoryIsCharacter", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $boug;

    /**
     * @var Story
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Story", inversedBy="bougStoryIsCharacter", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $story;

    /**
     * @var array
     *
     * @ORM\Column(name="Opinion", type="string", nullable=true)
     */
    private $opinion;


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
     * Set opinion
     *
     * @param array $opinion
     *
     * @return BougStoryIsCharacter
     */
    public function setOpinion($opinion)
    {
        $this->opinion = $opinion;

        return $this;
    }

    /**
     * Get opinion
     *
     * @return array
     */
    public function getOpinion()
    {
        return $this->opinion;
    }

    /**
     * Set boug
     *
     * @param \CoreBundle\Entity\Boug $boug
     *
     * @return BougStoryIsCharacter
     */
    public function setBoug(\CoreBundle\Entity\Boug $boug)
    {
        $this->boug = $boug;

        return $this;
    }

    /**
     * Get boug
     *
     * @return \CoreBundle\Entity\Boug
     */
    public function getBoug()
    {
        return $this->boug;
    }

    /**
     * Set story
     *
     * @param \CoreBundle\Entity\Story $story
     *
     * @return BougStoryIsCharacter
     */
    public function setStory(\CoreBundle\Entity\Story $story)
    {
        $this->story = $story;

        return $this;
    }

    /**
     * Get story
     *
     * @return \CoreBundle\Entity\Story
     */
    public function getStory()
    {
        return $this->story;
    }
}
