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
 * Place
 *
 * @ORM\Table(name="places")
 * @ExclusionPolicy("none")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaceRepository")
 */
class Place
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
     * @Groups({"place_list", "place_view"})
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Groups({"place_list", "place_view"})
     *
     * @Gedmo\Slug(fields={"name"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     * @Groups({"place_list", "place_view"})
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="short_description", type="text")
     */
    private $shortDescription;

    /**
     * @var integer
     * @Groups({"place_view"})
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var integer
     * @Groups({"place_view"})
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="price_range", type="integer")
     */
    private $priceRange;

    /**
     * @Groups({"place_view"})
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="places")
     * @ORM\JoinTable(name="places_categories")
     */
    private $categories;

    /**
     * @var string
     * @Groups({"place_view"})
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="full_description", type="text")
     */
    private $fullDescription;
    /**
     * @var string
     * @Groups({"place_view"})
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var decimal
     * @Groups({"place_view"})
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @var decimal
     * @Groups({"place_view"})
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     * @Groups({"place_view"})
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var string
     * @Groups({"place_view"})
     *
     * @ORM\Column(name="phonenumber", type="string", length=255, nullable=true)
     */
    private $phonenumber;

    /**
     * @var string
     * @Groups({"place_view"})
     *
     * @ORM\Column(name="opened", type="json_array", nullable=true)
     */
    private $opened;

    /**
     * @var string
     * @Exclude
     *
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    private $googleID;

    /**
     * A Unidirectional One-To-Many relation, built in Doctrine2 way
     * @Groups({"place_list", "place_view"})
     *
     * @ORM\ManyToMany(targetEntity="File", fetch="EAGER",  cascade={"all"})
     * @ORM\JoinTable(name="places_images",
     *      joinColumns={@ORM\JoinColumn(name="place_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true, onDelete="CASCADE")},
     *      )
     */
    private $images;

    // Virtual properties
    /**
     * @Groups({"place_list", "place_view"})
     */
    public $distance;

    /**
     * @Groups({"place_list", "place_view"})
     */
    public $distanceValue;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * Add category
     *
     * @param Category $category
     *
     * @return Place
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set fullDescription
     *
     * @param string $fullDescription
     *
     * @return Place
     */
    public function setFullDescription($fullDescription)
    {
        $this->fullDescription = $fullDescription;

        return $this;
    }

    /**
     * Get fullDescription
     *
     * @return string
     */
    public function getFullDescription()
    {
        return $this->fullDescription;
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

    /**
     * @VirtualProperty
     */
    public function distance()
    {
        return $this->distance;
    }

    /**
     * @VirtualProperty
     */
    public function distanceValue()
    {
        return $this->distanceValue;
    }
}
