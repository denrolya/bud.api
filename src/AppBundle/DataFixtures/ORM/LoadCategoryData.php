<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\File;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
        foreach($this->categories as $index => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);

            $this->addReference("category-$index", $category);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    private $categories = [
        'Cafes', 'Nightlife', 'Film & Culture', 'Health & Fitness', 'Bars & Pubs', 'Shopping'
    ];
}