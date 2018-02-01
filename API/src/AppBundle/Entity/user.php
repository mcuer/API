<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * user
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\userRepository")
 *
 * @Serializer\ExclusionPolicy("ALL")
 */
class user
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=16, unique=true)
     * 
     * @Serializer\Expose
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Serializer\Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32)
     *
     * @Serializer\Expose
     */
    private $password;

     /**
     * @var \message
     *
     * @ORM\OneToMany(targetEntity="message" , mappedBy="creator" , cascade={"remove","persist"})
     * @Serializer\Expose
     */
    private $messages;

    /**
     * @var \follow
     *
     * @ORM\OneToMany(targetEntity="follow" , mappedBy="follower" , cascade={"remove","persist"})
     * @Serializer\Expose
     */
    private $follows;

    /**
     * @var \follow
     *
     * @ORM\OneToMany(targetEntity="follow" , mappedBy="followed" , cascade={"remove","persist"})
     * @Serializer\Expose
     */
    private $followers;

    
    public function __construct() {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->follows = new \Doctrine\Common\Collections\ArrayCollection();
        $this->followers = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set username
     *
     * @param string $username
     *
     * @return user
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return user
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
     * Set password
     *
     * @param string $password
     *
     * @return user
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\message $message
     *
     * @return user
     */
    public function addMessage(\AppBundle\Entity\message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\message $message
     */
    public function removeMessage(\AppBundle\Entity\message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add follow
     *
     * @param \AppBundle\Entity\follow $follow
     *
     * @return user
     */
    public function addFollow(\AppBundle\Entity\follow $follow)
    {
        $this->follows[] = $follow;

        return $this;
    }

    /**
     * Remove follow
     *
     * @param \AppBundle\Entity\follow $follow
     */
    public function removeFollow(\AppBundle\Entity\follow $follow)
    {
        $this->follows->removeElement($follow);
    }

    /**
     * Get follows
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollows()
    {
        return $this->follows;
    }

    /**
     * Add follower
     *
     * @param \AppBundle\Entity\follow $follower
     *
     * @return user
     */
    public function addFollower(\AppBundle\Entity\follow $follower)
    {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove follower
     *
     * @param \AppBundle\Entity\follow $follower
     */
    public function removeFollower(\AppBundle\Entity\follow $follower)
    {
        $this->followers->removeElement($follower);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }
}
