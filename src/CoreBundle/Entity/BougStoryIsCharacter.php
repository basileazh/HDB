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
     * @var array
     *
     * @ORM\Column(name="Opinion", type="array", nullable=true)
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
}
