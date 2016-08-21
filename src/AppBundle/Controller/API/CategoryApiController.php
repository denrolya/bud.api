<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Category, AppBundle\Entity\File;
use AppBundle\Form\CategoryType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Class CategoryApiController
 * @package AppBundle\Controller
 */
class CategoryApiController extends FOSRestController
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
     * @Get("/categories/{categorySlug}", requirements={"categorySlug" = "^(?!files)[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("category", class="AppBundle:Category", options={"mapping": {"categorySlug": "slug"}})
     */
    public function getCategoryAction(Category $category)
    {
        return $category;
    }

    /**
     * @Post("/categories/{categorySlug}", requirements={"categorySlug" = "^(?!files)[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("category", class="AppBundle:Category", options={"mapping": {"categorySlug": "slug"}})
     */
    public function editCategoryAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

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

            try {
                $file->move($this->getParameter('categories_dir'), $filename);
            } catch(FileException $e) {
                throw new HttpException(500, $e->getMessage());
            }

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
     * @Post("/categories/{categorySlug}/files", requirements={"categorySlug" = "^(?!files)[a-z0-9]+(?:-[a-z0-9]+)*$"})
     * @ParamConverter("category", class="AppBundle:Category", options={"mapping": {"categorySlug": "slug"}})
     */
    public function updateCategoryCoverAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $file = $request->files->get('uploadfile');
        $status = ['status' => 'success', 'fileUploaded' => false];

        if (!is_null($file)) {
            $fs = new Filesystem();

            $oldCoverImage = $category->getCoverImage();

            $fs->remove($oldCoverImage->getAbsolutePath());
            $em->remove($oldCoverImage);

            $filename = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move($this->getParameter('categories_dir'), $filename);
            } catch(FileException $e) {
                throw new HttpException(500, $e->getMessage());
            }

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
}
