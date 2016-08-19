<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\File;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadCategoryData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        foreach($this->categories as $categoryName) {
            $files = glob($this->container->getParameter('uploads_dir') . '/fixtures/*');
            $randomIndex = array_rand($files);
            $randomFile = new \Symfony\Component\HttpFoundation\File\File($files[$randomIndex]);

            $coverImage = new File();
            $coverImage
                ->setName($randomFile->getFilename())
                ->setSize($randomFile->getSize())
                ->setAbsolutePath($randomFile->getRealPath())
                ->setUri('http://bud.api/uploads/fixtures/' . $randomFile->getFilename());
            $manager->persist($coverImage);

            $category = new Category();
            $category->setName($categoryName)
                ->setCoverImage($coverImage);

            $manager->persist($category);
        }

        $manager->flush();
    }

    private $categories = [
        'Cafes', 'Nightlife', 'Film & Culture', 'Health & Fitness', 'Bars & Pubs', 'Shopping'
    ];
}