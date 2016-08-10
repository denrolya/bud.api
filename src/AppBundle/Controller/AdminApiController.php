<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category, AppBundle\Entity\Event, AppBundle\Entity\File;
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

        $data = $request->request;

        $em = $this->getDoctrine()->getManager();

        $newCategory = (new Category())
            ->setName($request->get('name'))
            ->setCoverImage($em->getReference(File::class, $request->get('coverImage')));

        $em->persist($newCategory);

        $em->flush();

        return ['new_category' => $newCategory];
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
            $filename = $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($this->getParameter('categories_dir'), $filename); // move the file to a path

            $newFile = (new File())
                ->setName($filename)
                ->setRelativePath('/uploads/categories/' . $filename)
                ->setAbsolutePath($this->getParameter('categories_dir') . '/' . $filename)
                ->setSize($file->getClientSize());

            $em->persist($newFile);

            $em->flush();

            // generate a random name for the file but keep the extension

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newFile->getId()];
        }

        return new JsonResponse($status);
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
        // TODO: Create category form class and validate $request with it
        // TODO: Or just hook a custom validator with it

        $data = $request->request;
        $em = $this->getDoctrine()->getManager();

        $newEvent = (new Event())
            ->setTitle($data->get('title'))
            ->setDescriptionBlock1($data->get('descriptionBlock1'))
            ->setDescriptionBlock2($data->get('descriptionBlock2'))
            ->setDateFrom(new \DateTime($data->get('dateFrom')))
            ->setDateTo($data->get('dateTo') ? new \DateTime($data->get('dateTo')) : NULL)
            ->setLocation($data->get('location'));

        $em->persist($newEvent);
        $em->flush();

        if (count($data->get('images')) > 0) {
            $fs = new Filesystem();

            $eventDir = $this->getParameter('events_dir') . '/' . $newEvent->getId() . '-' . $newEvent->getSlug();

            // TODO: Exception handling here!
            $fs->mkdir($eventDir);

            foreach($data->get('images') as $index => $image) {
                $image = $em->getRepository(File::class)->find($image['file_id']);

                // TODO: Exception handling here!
                (new SymfonyFile($image->getAbsolutePath()))->move($eventDir, $image->getName());

                $image->setAbsolutePath($eventDir . '/' . $image->getName())
                    ->setRelativePath('/' . $fs->makePathRelative($eventDir, $this->getParameter('web_dir')) . $image->getName());

                $newEvent->addImage($image);

                $em->flush();
            }
        }

        return ['new_event' => $newEvent];
    }

    /**
     * @Get("/events/{eventSlug}", requirements={"eventSlug" = ".*"})
     */
    public function getEventAction($eventSlug)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)
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
            $formData = $form->getData();

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

            $filename = $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($eventDir, $filename);

            $newFile = (new File())
                ->setName($filename)
                ->setAbsolutePath($eventDir . '/' . $filename)
                ->setRelativePath('/' . $fs->makePathRelative($eventDir, $this->getParameter('web_dir')) . $filename)
                ->setSize($file->getClientSize());

            $em->persist($newFile);

            $event->addImage($newFile);

            $em->flush();

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newFile->getId()];
        }

        return new JsonResponse($status);
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
            $filename = $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // TODO: Exception handling here!
            $file->move($this->getParameter('temp_dir'), $filename); // move the file to a path

            $newFile = (new File())
                ->setName($filename)
                ->setRelativePath('/uploads/tmp/' . $filename)
                ->setAbsolutePath($this->getParameter('temp_dir') . '/' . $filename)
                ->setSize($file->getClientSize());

            $em->persist($newFile);

            $em->flush();

            // generate a random name for the file but keep the extension

            $status = ['status' => "success", "fileUploaded" => true, 'file_id' => $newFile->getId()];
        }

        return new JsonResponse($status);
    }
}
