<?php

namespace LandingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return $this->forward('LandingBundle:Default:comingSoon');
    }

    /**
     * @Route("/coming-soon")
     * @Template()
     */
    public function comingSoonAction()
    {
        return [];
    }

    /**
     * @Route("/landing")
     * @Template("@Landing/Default/index.html.twig")
     */
    public function indexFallbackAction()
    {
        return [];
    }
}
