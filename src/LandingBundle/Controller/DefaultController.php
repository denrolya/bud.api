<?php

namespace LandingBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use LandingBundle\Entity\PotentialClient;
use LandingBundle\Form\PotentialClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
    public function comingSoonAction(Request $request)
    {
        $potentialClient = new PotentialClient();
        $form = $this->createForm(PotentialClientType::class, $potentialClient);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $potentialClient = $form->getData();

            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($potentialClient);
                $em->flush();

                return new JsonResponse(['result' => 'ok']);
            } catch(ORMException $e) {
                return new JsonResponse(['error' => 'Something wicked happened'], 500);
            } catch(UniqueConstraintViolationException $e) {
                return new JsonResponse(['error' => 'Current email was already submitted!'], 500);
            } catch(\Exception $e) {
                return new JsonResponse(['error' => 'Something wicked happened'], 500);
            }
        }

        return ['form' => $form];
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
