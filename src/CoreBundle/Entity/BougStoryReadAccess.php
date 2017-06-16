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
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Boug", inversedBy="storiesAccess", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $boug;

    /**
     * @var Story
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Story", inversedBy="bougAccess", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $story;

    /**
     * @var array
     *
     * @ORM\Column(name="Rating", type="array", nullable=true)
     */
    private $rating;


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
     * Set boug
     *
     * @param \CoreBundle\Entity\Boug $boug
     *
     * @return BougStoryReadAccess
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
     * @return BougStoryReadAccess
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

    /**
     * Set rating
     *
     * @param array $rating
     *
     * @return BougStoryReadAccess
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return array
     */
    public function getRating()
    {
        return $this->rating;
    }
}
