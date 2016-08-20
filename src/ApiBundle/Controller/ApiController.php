<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Event;
use AppBundle\Entity\Place;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends FOSRestController
{
    /**
     * Get all categories
     *
     * @Get("/categories")
     */
    public function getCategoriesAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return [
            'categories' => $categories
        ];
    }

    /**
     * Get all places in categories
     *
     * @Get("/categories/{categorySlug}/places")
     */
    public function getCategoryPlacesAction($categorySlug)
    {
        $category = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBySlug($categorySlug);

        if (!$category) {
            // throw exception
        }

        return [
            'places' => $category->getPlaces()
        ];
    }

    /**
     * Get place in categories
     *
     * @Get("/places/{placeSlug}")
     */
    public function getPlaceAction($placeSlug)
    {
        $place = $this
            ->getDoctrine()
            ->getRepository(Place::class)
            ->findOneBySlug($placeSlug);

        if (!$place) {
            // throw exception
        }

        return [
            'place' => $place
        ];
    }

    /**
     * Get all Events
     *
     * @Get("/events")
     */
    public function getEventsAction()
    {
        $events = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        return [
            'events' => $events
        ];
    }
}
