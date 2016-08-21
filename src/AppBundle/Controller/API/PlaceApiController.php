<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Category, AppBundle\Entity\Place, AppBundle\Entity\File;
use AppBundle\Form\PlaceType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @Get("/places/{placeSlug}", requirements={"placeSlug" = "^(?!files)[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("place", class="AppBundle:Place", options={"mapping": {"placeSlug": "slug"}})
     */
    public function getPlaceAction(Place $place)
    {
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

                try {
                    $fs->mkdir($placeDir);
                } catch (IOExceptionInterface $e) {
                    throw new HttpException(500, "An error occurred while creating your directory at ".$e->getPath());
                }

                foreach ($files as $index => $image) {
                    $image = $em->getRepository(File::class)->find($image['file_id']);

                    try {
                        (new SymfonyFile($image->getAbsolutePath()))->move($placeDir, $image->getName());
                    } catch(FileException $e) {
                        throw new HttpException(500, $e->getMessage());
                    }

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

            try {
                $file->move($this->getParameter('temp_dir'), $filename);
            } catch(FileException $e) {
                throw new HttpException(500, $e->getMessage());
            }

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
     * @Post("/places/{placeSlug}", requirements={"placeSlug" = "^(?!files)[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("place", class="AppBundle:Place", options={"mapping": {"placeSlug": "slug"}})
     */
    public function editPlaceAction(Place $place, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

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
     * Remove existing place's file
     *
     * @Delete("/places/{placeSlug}/files/{fileId}", requirements={"placeSlug" = ".*", "fileId" = "\d+"})
     * @ParamConverter("place", class="AppBundle:Place", options={"mapping": {"placeSlug": "slug"}})
     * @ParamConverter("file", class="AppBundle:File", options={"mapping": {"fileId": "id"}})
     */
    public function removePlaceFileAction(Place $place, File $file)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($file);
        $em->flush();

        return [
            'success' => true
        ];
    }
}
