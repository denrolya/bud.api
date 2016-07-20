<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="coverImageFilePath", type="string", length=255, unique=true, nullable=true)
     */
    private $coverImageFilePath;


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
     * @return Category
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
     * Set coverImageFilePath
     *
     * @param string $coverImageFilePath
     *
     * @return Category
     */
    public function setCoverImageFilePath($coverImageFilePath)
    {
        $this->coverImageFilePath = $coverImageFilePath;

        return $this;
    }

    /**
     * Get coverImageFilePath
     *
     * @return string
     */
    public function getCoverImageFilePath()
    {
        return $this->coverImageFilePath;
    }
}

