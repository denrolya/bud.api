<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category, AppBundle\Entity\Event, AppBundle\Entity\File;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
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

                $em->flush();

                $newEvent->addImage($image);
            }
        }

        return ['new_event' => $newEvent];
    }

    /**
     * Post category file
     *
     * @Post("/events/files")
     */
    public function postEventFileAction(Request $request)
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
