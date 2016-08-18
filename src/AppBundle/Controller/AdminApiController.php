<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category, AppBundle\Entity\Event, AppBundle\Entity\File;
use AppBundle\Form\CategoryType;
use AppBundle\Form\EventType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Class AdminApiController
 * @package AppBundle\Controller
 */
class AdminApiController extends FOSRestController
{
    /**
     * Search for items
     *
     * @Get("/search/{query}", requirements={"query" = ".*"})
     */
    public function searchAction($query)
    {
        $finder = $this->container->get('fos_elastica.finder.budapp');

        $result = $finder->find($query);

        return [
            'result' => $result
        ];
    }

    /**
     * @Get("/categories")
     */
    public function getCategoriesAction()
    {
        return [
            'categories' => $this->getDoctrine()->getRepository(Category::class)->findAll()
        ];
    }

    /**
     * @Post("/categories")
     */
    public function createCategoryAction(Request $request)
    {
        // TODO: Create category form class and validate $request with it
        // TODO: Or just hook a custom validator with it
        $em = $this->getDoctrine()->getManager();
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $category->setCoverImage($em->getReference(File::class, $request->get('category')['coverImage']));

            $em->persist($category);
            $em->flush();
        }

        return ['new_category' => $category];
    }

    /**
     * @Get("/categories/{categorySlug}", requirements={"categorySlug" = "^(?!files$).*"})
     */
    public function getCategoryAction($categorySlug)
    {
        $category = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBySlug($categorySlug);

        return $category;
    }

    /**
     * @Post("/categories/{categorySlug}", requirements={"categorySlug" = "^(?!files$).*"})
     */
    public function editCategoryAction($categorySlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$category = $em->getRepository(Category::class)->findOneBySlug($categorySlug)) {
            // throw exception
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $response = ['category' => $category];
        } else {
            $response = ['category' => false];
        }

        return $response;
    }

    /**
     * Post category file
     *
     * @Post("/categories/files")
     */
    public function postCategoryFileAction(Request $request)
    {
        $file = $request->files->get('uploadfile');
        $status = ['status' => "success", "fileUploaded" => false];

        // If a file was uploaded
        if(!is_null($file)){
            $em = $this->getDoctrine()->getManager();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($this->getParameter('categories_dir'), $filename);

            // TODO: Refactor URI Generation
            $fileUri = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/categories/' . $filename;
            $newFile = (new File())
                ->setName($filename)
                ->setUri($fileUri)
                ->setAbsolutePath($this->getParameter('categories_dir') . '/' . $filename)
                ->setSize($file->getClientSize());

            $em->persist($newFile);

            $em->flush();

            // generate a random name for the file but keep the extension

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newFile->getId()];
        }

        return $status;
    }

    /**
     * @Post("/categories/{categorySlug}/files", requirements={"categorySlug" = "^(?!files$).*"})
     */
    public function updateCategoryCoverAction($categorySlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$category = $em->getRepository(Category::class)->findOneBySlug($categorySlug)) {
            // throw exception
        }

        $file = $request->files->get('uploadfile');
        $status = ['status' => 'success', 'fileUploaded' => false];

        if (!is_null($file)) {
            $fs = new Filesystem();

            $oldCoverImage = $category->getCoverImage();

            $fs->remove($oldCoverImage->getAbsolutePath());
            $em->remove($oldCoverImage);

            $filename = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($this->getParameter('categories_dir'), $filename);

            // TODO: Refactor URI Generation
            $fileUri = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/categories/' . $filename;
            $newCoverImage = (new File())
                ->setName($filename)
                ->setUri($fileUri)
                ->setAbsolutePath($this->getParameter('categories_dir') . '/' . $filename)
                ->setSize($file->getClientSize());

            $em->persist($newCoverImage);

            $category->setCoverImage($newCoverImage);

            $em->flush();

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newCoverImage->getId()];
        }

        return $status;
    }

    /**
     * @Get("/events")
     */
    public function getAllEventsAction()
    {
        return [
            'events' => $this->getDoctrine()->getRepository(Event::class)->findAll()
        ];
    }

    /**
     * @Post("/events")
     */
    public function createEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($event);
            $em->flush();

            $files = $request->get('event')['images'];
            if (count($files) > 0) {
                $fs = new Filesystem();

                $eventDir = $this->getParameter('events_dir') . '/' . $event->getId() . '-' . $event->getSlug();

                // TODO: Exception handling here!
                $fs->mkdir($eventDir);

                foreach($files as $index => $image) {
                    $image = $em->getRepository(File::class)->find($image['file_id']);

                    // TODO: Exception handling here!
                    (new SymfonyFile($image->getAbsolutePath()))->move($eventDir, $image->getName());

                    // TODO: Refactor URI Generation
                    $fileUri = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/events/' .$event->getId() . '-' . $event->getSlug()  . '/' . $image->getName();
                    $image
                        ->setAbsolutePath($eventDir . '/' . $image->getName())
                        ->setUri($fileUri);

                    $event->addImage($image);

                    $em->flush();
                }
            }

            $response = ['event' => $event];
        } else {
            $response = ['event' => false];
        }

        return $response;
    }

    /**
     * @Get("/events/{eventSlug}", requirements={"eventSlug" = ".*"})
     */
    public function getEventAction($eventSlug)
    {
        $event = $this
            ->getDoctrine()
            ->getRepository(Event::class)
            ->findOneBySlug($eventSlug);

        return $event;
    }

    /**
     * @Post("/events/{eventSlug}", requirements={"eventSlug" = "^(?!files$).*"})
     */
    public function editEventAction($eventSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$event = $em->getRepository(Event::class)->findOneBySlug($eventSlug)) {
            // throw exception
        }

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $response = ['event' => $event];
        } else {
            $response = ['event' => false];
        }

        return $response;
    }

    /**
     * Post existing event file
     *
     * @Post("/events/{eventSlug}/files", requirements={"slug" = ".*"})
     */
    public function postExistingEventFileAction($eventSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$event = $em->getRepository(Event::class)->findOneBySlug($eventSlug)) {
            // throw exception
        }

        $file = $request->files->get('uploadfile');
        $status = ['status' => 'success', 'fileUploaded' => false];

        if (!is_null($file)) {
            $fs = new Filesystem();

            $eventDir = $this->getParameter('events_dir') . '/' . $event->getId() . '-' . $event->getSlug();

            $filename = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($eventDir, $filename);

            // TODO: Refactor URI Generation
            $fileUri = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/events/' .$event->getId() . '-' . $event->getSlug()  . '/' . $filename;
            $newFile = (new File())
                ->setName($filename)
                ->setAbsolutePath($eventDir . '/' . $filename)
                ->setUri($fileUri)
                ->setSize($file->getClientSize());

            $em->persist($newFile);

            $event->addImage($newFile);

            $em->flush();

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newFile->getId()];
        }

        return $status;
    }

    /**
     * Remove existing event file
     *
     * @Delete("/events/{eventSlug}/files/{fileId}", requirements={"slug" = ".*", "fileId" = "\d+"})
     */
    public function removeEventFileAction($eventSlug, $fileId)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$event = $em->getRepository(Event::class)->findOneBySlug($eventSlug)) {
            // throw exception
        }

        if (!$file = $em->getRepository(File::class)->find($fileId)) {
            // throw exception
        }

        $em->remove($file);

        $em->flush();


        return [
            'success' => true
        ];
    }

    /**
     * Post new event file
     *
     * @Post("/events/files")
     */
    public function postNewEventFileAction(Request $request)
    {
        $file = $request->files->get('uploadfile');
        $status = ['status' => "success", "fileUploaded" => false];

        // If a file was uploaded
        if(!is_null($file)){
            $em = $this->getDoctrine()->getManager();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($this->getParameter('temp_dir'), $filename);

            // TODO: Refactor URI Generation
            $fileUri = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/tmp/' . $filename;

            $newFile = (new File())
                ->setName($filename)
                ->setUri($fileUri)
                ->setAbsolutePath($this->getParameter('temp_dir') . '/' . $filename)
                ->setSize($file->getClientSize());

            $em->persist($newFile);

            $em->flush();

            // generate a random name for the file but keep the extension

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newFile->getId()];
        }

        return $status;
    }
}
