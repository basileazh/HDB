<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BougStoryReadAccess
 *
 * @ORM\Table(name="boug_story_read_access")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\BougStoryReadAccessRepository")
 */
class BougStoryReadAccess
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
     * @ORM\ManyToOne(targetEntity="HDB\CoreBundle\Entity\Boug", inversedBy="storyAccess")
     * @ORM\JoinColumn(nullable=false)
     */
    private $boug;

    /**
     * @var Story
     *
     * @ORM\ManyToOne(targetEntity="HDB\CoreBundle\Entity\Story", inversedBy="bougAccess")
     * @ORM\JoinColumn(nullable=false)
     */
    private $story;

    /**
     * @var array
     *
     * @ORM\Column(name="Note", type="array", nullable=true)
     */
    private $note;


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
     * Set note
     *
     * @param array $note
     *
     * @return BougStoryReadAccess
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return array
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set boug
     *
     * @param \HDB\CoreBundle\Entity\Boug $boug
     *
     * @return BougStoryReadAccess
     */
    public function setBoug(\HDB\CoreBundle\Entity\Boug $boug)
    {
        $this->boug = $boug;

        return $this;
    }

    /**
     * Get boug
     *
     * @return \HDB\CoreBundle\Entity\Boug
     */
    public function getBoug()
    {
        return $this->boug;
    }

    /**
     * Set story
     *
     * @param \HDB\CoreBundle\Entity\Story $story
     *
     * @return BougStoryReadAccess
     */
    public function setStory(\HDB\CoreBundle\Entity\Story $story)
    {
        $this->story = $story;

        return $this;
    }

    /**
     * Get story
     *
     * @return \HDB\CoreBundle\Entity\Story
     */
    public function getStory()
    {
        return $this->story;
    }
}