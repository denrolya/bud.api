<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\File;
use JMS\Serializer\Annotation\Groups, JMS\Serializer\Annotation\ExclusionPolicy,
    JMS\Serializer\Annotation\Exclude, JMS\Serializer\Annotation\VirtualProperty;

/**
 * Category
 *
 * @ORM\Table(name="categories")
 * @ExclusionPolicy("none")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     * @Exclude()
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Groups({"category_list"})
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     * @Groups({"category_list"})
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @Exclude()
     *
     * @ORM\ManyToMany(targetEntity="Place", mappedBy="categories")
     */
    private $places;

    /**
     * @var File
     * @Groups({"category_list"})
     *
     * @ORM\OneToOne(targetEntity="File", fetch="EAGER", cascade={"all"})
     * @ORM\JoinColumn(name="cover_image_id", referencedColumnName="id", unique=true, nullable=true, onDelete="CASCADE")
     */
    private $coverImage;

    public function __construct()
    {
        $this->places = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set coverImage
     *
     * @param File $coverImage
     *
     * @return Category
     */
    public function setCoverImage(File $coverImage)
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * Get coverImage
     *
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    /**
     * Add place
     *
     * @param \AppBundle\Entity\Place $place
     *
     * @return Category
     */
    public function addPlace(\AppBundle\Entity\Place $place)
    {
        $this->places[] = $place;

        return $this;
    }

    /**
     * Remove place
     *
     * @param \AppBundle\Entity\Place $place
     */
    public function removePlace(\AppBundle\Entity\Place $place)
    {
        $this->places->removeElement($place);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @Groups({"category_list"})
     * @VirtualProperty
     */
    public function placesCount()
    {
        return $this->getPlaces()->count();
    }
}
