<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Category, AppBundle\Entity\Place, AppBundle\Entity\File;
use AppBundle\Form\PlaceType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Class PlaceApiController
 * @package AppBundle\Controller
 */
class PlaceApiController extends FOSRestController
{
    /**
     * @Get("/places")
     */
    public function getAllPlacesAction()
    {
        return [
            'places' => $this->getDoctrine()->getRepository(Place::class)->findAll()
        ];
    }

    /**
     * @Get("/places/{placeSlug}", requirements={"placeSlug" = ".*"})
     */
    public function getPlaceAction($placeSlug)
    {
        $place = $this
            ->getDoctrine()
            ->getRepository(Place::class)
            ->findOneBySlug($placeSlug);

        return $place;
    }

    /**
     * @Post("/places")
     */
    public function createPlaceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em->persist($place);
            $em->flush();

            $files = $request->get('place')['images'];
            if (count($files) > 0) {
                $fs = new Filesystem();

                $placeDir = $this->getParameter('places_dir') . '/' . $place->getId() . '-' . $place->getSlug();

                // TODO: Exception handling here!
                $fs->mkdir($placeDir);

                foreach ($files as $index => $image) {
                    $image = $em->getRepository(File::class)->find($image['file_id']);

                    // TODO: Exception handling here!
                    (new SymfonyFile($image->getAbsolutePath()))->move($placeDir, $image->getName());

                    // TODO: Refactor URI Generation
                    $fileUri = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/places/' . $place->getId() . '-' . $place->getSlug() . '/' . $image->getName();
                    $image
                        ->setAbsolutePath($placeDir . '/' . $image->getName())
                        ->setUri($fileUri);

                    $place->addImage($image);

                    $em->flush();
                }
            }

            $response = ['place' => $place];
        } else {
            $response = ['place' => false];
        }

        return $response;
    }

    /**
     * @Post("/places/{placeSlug}", requirements={"placeSlug" = "^(?!files$).*"})
     */
    public function editPlaceAction($placeSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$place = $em->getRepository(Place::class)->findOneBySlug($placeSlug)) {
            // throw exception
        }

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $response = ['place' => $place];
        } else {
            $response = ['place' => false];
        }

        return $response;
    }

    /**
     * Post new place's file
     *
     * @Post("/places/files")
     */
    public function postNewPlaceFileAction(Request $request)
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

    /**
     * Remove existing place's file
     *
     * @Delete("/places/{placeSlug}/files/{fileId}", requirements={"placeSlug" = ".*", "fileId" = "\d+"})
     */
    public function removePlaceFileAction($placeSlug, $fileId)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$place = $em->getRepository(Place::class)->findOneBySlug($placeSlug)) {
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
}
