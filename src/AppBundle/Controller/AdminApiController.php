<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Post("/category")
     */
    public function createCategoryAction(Request $request)
    {
        // TODO: Create category form class and validate $request with it
        // TODO: Or just hook a custom validator with it

        $data = $request->request;

        $em = $this->getDoctrine()->getManager();

        $newCategory = (new Category())
            ->setName($request->get('name'))
            ->setCoverImageFilePath('/uploads/categories' . $request->get('coverImage'));

        $em->persist($newCategory);

        $em->flush();

        return ['new_category' => $newCategory];
    }

    /**
     * Post category file
     *
     * @Post("/files")
     */
    public function postCategoryFileAction(Request $request)
    {
        $file = $request->files->get('uploadfile');
        $status = ['status' => "success", "fileUploaded" => false];

        // If a file was uploaded
        if(!is_null($file)){
            // generate a random name for the file but keep the extension
            $filename = $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('categories_directory'), $filename); // move the file to a path
            $status = ['status' => "success", "fileUploaded" => true, 'filename' => $filename];
        }

        return new JsonResponse($status);
    }
}
