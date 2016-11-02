<?php

namespace LandingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PotentialClient
 *
 * @ORM\Table(name="potential_client")
 * @ORM\Entity(repositoryClass="LandingBundle\Repository\PotentialClientRepository")
 */
class PotentialClient
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="isEmailSent", type="boolean")
     */
    private $isEmailSent = false;


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
     * Set email
     *
     * @param string $email
     *
     * @return PotentialClient
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
     * Set isEmailSent
     *
     * @param boolean $isEmailSent
     *
     * @return PotentialClient
     */
    public function setIsEmailSent($isEmailSent)
    {
        $this->isEmailSent = $isEmailSent;

        return $this;
    }

    /**
     * Get isEmailSent
     *
     * @return bool
     */
    public function getIsEmailSent()
    {
        return $this->isEmailSent;
    }
}

