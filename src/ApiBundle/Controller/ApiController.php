<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Category;
use ApiBundle\Entity\Event;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends FOSRestController
{
    /**
     * Get all categories
     *
     * @Get("/members")
     */
    public function getCategoriesAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return ['categories' => $categories];
    }

    /**
     * Get all Events
     *
     * @Get("/events")
     */
    public function getEventsAction()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return ['events' => $events];
    }
}
