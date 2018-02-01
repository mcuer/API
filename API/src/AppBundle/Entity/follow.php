<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * follow
 *
 * @ORM\Table(name="follow")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\followRepository")
 * 
 * @Serializer\ExclusionPolicy("ALL")
 */
class follow
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="user", inversedBy="follows")
     * @Serializer\Expose
     */
    private $follower;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="user", inversedBy="followers")
     * 
     * @Serializer\Expose
     */
    private $followed;


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
     * Set follower
     *
     * @param \AppBundle\Entity\user $follower
     *
     * @return follow
     */
    public function setFollower(\AppBundle\Entity\user $follower = null)
    {
        $this->follower = $follower;

        return $this;
    }

    /**
     * Get follower
     *
     * @return \AppBundle\Entity\user
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * Set followed
     *
     * @param \AppBundle\Entity\user $followed
     *
     * @return follow
     */
    public function setFollowed(\AppBundle\Entity\user $followed = null)
    {
        $this->followed = $followed;

        return $this;
    }

    /**
     * Get followed
     *
     * @return \AppBundle\Entity\user
     */
    public function getFollowed()
    {
        return $this->followed;
    }
}
