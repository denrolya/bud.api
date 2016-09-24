<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Event;
use AppBundle\Entity\Place;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;

class ApiController extends FOSRestController
{
    /**
     * Get all categories
     *
     * @View(serializerGroups={"category_list"})
     * @Get("/categories")
     */
    public function getCategoriesAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findNotEmptyCategories();

        return [
            'categories' => $categories
        ];
    }

    /**
     * Get all places in categories
     *
     * @View(serializerGroups={"place_list"})
     * @Get("/categories/{categorySlug}/places", requirements={"categorySlug" = "^[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("category", class="AppBundle:Category", options={"mapping": {"categorySlug": "slug"}})
     */
    public function getCategoryPlacesAction(Request $request, Category $category)
    {
        $places = (!$request->get('closest'))
            ? $category->getPlaces()
            : $this->getDoctrine()->getRepository(Place::class)->getClosestPlacesInCategory([
                    'latitude' => $request->get('latitude'),
                    'longitude' => $request->get('longitude')
                ], $category);

        return [
            'places' => $places
        ];
    }


    /**
     * Get place
     *
     * @View(serializerGroups={"place_view"})
     * @Get("/places/{placeSlug}", requirements={"placeSlug" = "^[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("place", class="AppBundle:Place", options={"mapping": {"placeSlug": "slug"}})
     */
    public function getPlaceAction(Place $place)
    {
        return [
            'place' => $place
        ];
    }

    /**
     * Get distance to places
     * @Get("/distance")
     */
    public function getDistanceToPlacesAction(Request $request)
    {
        $slugs = $request->query->get('placesSlugs');

        $placesRepo = $this->getDoctrine()->getRepository(Place::class);
        $places = $placesRepo->findBy(['slug' => $slugs]);

        $distances = $placesRepo->getDistanceToPlaces($places, [
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude')
        ]);

        $response = [];

        foreach($distances->rows[0]->elements as $index => $distance) {
            $response[] = [
                'slug' => $slugs[$index],
                'distance' => $distance->distance->text,
                'distanceValue' => $distance->distance->value
            ];
        }

        return [
            'data' => $response
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

    /**
     * @Get("/events/grouped")
     */
    public function getEventsGrouppedAction()
    {
        return [
            'events' => $this->getDoctrine()->getRepository(Event::class)->getEventsGroupedByDateStartingFromToday()
        ];
    }

    /**
     * Get event
     *
     * @Get("/events/{eventSlug}", requirements={"eventSlug" = "^[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("event", class="AppBundle:Event", options={"mapping": {"eventSlug": "slug"}})
     */
    public function getEventAction(Event $event)
    {
        return [
            'event' => $event
        ];
    }

    /**
     * Search
     *
     * @Get("/search/{searchQuery}", requirements={})
     */
    public function searchAction($searchQuery)
    {
        $finder = $this->container->get('fos_elastica.finder.budapp.place');
        $places = $finder->find("*$searchQuery*");

        return [
            'places' => $places
        ];
    }
}
