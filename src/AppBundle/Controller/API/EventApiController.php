<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Event, AppBundle\Entity\File;
use AppBundle\Form\EventType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Class EventApiController
 * @package AppBundle\Controller
 */
class EventApiController extends FOSRestController
{
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
     * @Get("/events/grouped")
     */
    public function getEventsGrouppedAction()
    {
        return [
            'events' => $this->getDoctrine()->getRepository(Event::class)->getEventsGroupedByDateStartingFromToday()
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
     * @Delete("/events/{eventSlug}/files/{fileId}", requirements={"eventSlug" = ".*", "fileId" = "\d+"})
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
