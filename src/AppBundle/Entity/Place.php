<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\File;

/**
 * Place
 *
 * @ORM\Table(name="places")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaceRepository")
 */
class Place
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="short_description", type="text")
     */
    private $shortDescription;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="price_range", type="integer")
     */
    private $priceRange;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="places")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category;

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
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var decimal
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var decimal
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="phonenumber", type="string", length=255, nullable=true)
     */
    private $phonenumber;

    /**
     * @var string
     *
     * @ORM\Column(name="opened", type="string", length=255, nullable=true)
     */
    private $opened;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    private $googleID;

    /**
     * A Unidirectional One-To-Many relation, built in Doctrine2 way
     *
     * @ORM\ManyToMany(targetEntity="File", fetch="EAGER",  cascade={"all"})
     * @ORM\JoinTable(name="places_images",
     *      joinColumns={@ORM\JoinColumn(name="place_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true, onDelete="CASCADE")},
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
     * Set place
     *
     * @param string $place
     *
     * @return Place
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
     * Set shortDescription
     *
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set priceRange
     *
     * @param integer $priceRange
     */
    public function setPriceRange($priceRange)
    {
        $this->priceRange = $priceRange;

        return $this;
    }

    /**
     * Get priceRange
     *
     * @return integer
     */
    public function getPriceRange()
    {
        return $this->priceRange;
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set descriptionBlock1
     *
     * @param string $descriptionBlock1
     *
     * @return Place
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
     * @return Place
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
     * Set address
     *
     * @param string $address
     *
     * @return Place
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Place
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Place
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Place
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phonenumber
     *
     * @param string $phonenumber
     *
     * @return Place
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    /**
     * Get phonenumber
     *
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * Set opened
     *
     * @param string $opened
     *
     * @return Place
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * Get opened
     *
     * @return string
     */
    public function getOpened()
    {
        return $this->opened;
    }

    /**
     * Set googleID
     *
     * @param string $googleID
     *
     * @return Place
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * Add image
     *
     * @param File $image
     *
     * @return Place
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
