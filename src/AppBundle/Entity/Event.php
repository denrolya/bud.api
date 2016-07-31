<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\File;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="description_block_1", type="text")
     */
    private $descriptionBlock1;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="description_block_2", type="text")
     */
    private $descriptionBlock2;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="dateFrom", type="datetime")
     */
    private $dateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTo", type="datetime", nullable=true)
     */
    private $dateTo;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * A Unidirectional One-To-Many relation, built by means of Doctrine2
     *
     * @ORM\ManyToMany(targetEntity="File", fetch="EAGER")
     * @ORM\JoinTable(name="events_images",
     *      joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $images;

    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set descriptionBlock1
     *
     * @param string $descriptionBlock1
     *
     * @return Event
     */
    public function setDescriptionBlock1($descriptionBlock1)
    {
        $this->descriptionBlock1 = $descriptionBlock1;

        return $this;
    }

    /**
     * Get descriptionBlock1
     *
     * @return string
     */
    public function getDescriptionBlock1()
    {
        return $this->descriptionBlock1;
    }

    /**
     * Set descriptionBlock2
     *
     * @param string $descriptionBlock2
     *
     * @return Event
     */
    public function setDescriptionBlock2($descriptionBlock2)
    {
        $this->descriptionBlock2 = $descriptionBlock2;

        return $this;
    }

    /**
     * Get descriptionBlock1
     *
     * @return string
     */
    public function getDescriptionBlock2()
    {
        return $this->descriptionBlock2;
    }

    /**
     * Set dateFrom
     *
     * @param \DateTime $dateFrom
     *
     * @return Event
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get dateFrom
     *
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     *
     * @return Event
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * Get dateTo
     *
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Event
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Add image
     *
     * @param File $image
     *
     * @return Event
     */
    public function addImage(File $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param File $image
     */
    public function removeImage(File $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
