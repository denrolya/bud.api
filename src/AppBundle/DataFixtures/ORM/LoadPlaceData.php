<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\File,
    AppBundle\Entity\Place;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPlaceData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
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
        $faker = Factory::create();

        for($i = 0; $i <= 50; $i++) {
            $placeImages = [];
            for($j = 0; $j <= rand(2,5); $j++) {
                $files = glob($this->container->getParameter('uploads_dir') . '/fixtures/*');
                $randomIndex = array_rand($files);
                $randomFile = new \Symfony\Component\HttpFoundation\File\File($files[$randomIndex]);

                $placeImage = new File();
                $placeImage
                    ->setName($randomFile->getFilename())
                    ->setSize($randomFile->getSize())
                    ->setAbsolutePath($randomFile->getRealPath())
                    ->setUri('http://bud.api/uploads/fixtures/' . $randomFile->getFilename());
                $manager->persist($placeImage);

                array_push($placeImages, $placeImage);
            }

            $place = new Place();
            $place
                ->setName(ucfirst($faker->words(2, true)))
                ->setShortDescription($faker->sentences(1, true))
                ->setRating(rand(1,5))
                ->setPriceRange(rand(1,5))
                ->setFullDescription("<p>".implode('</p><p>', $faker->paragraphs(3))."</p>")
                ->setAddress($faker->address)
                ->setLatitude(47.5088783)
                ->setLongitude(19.0617446)
                ->setPhonenumber($faker->phoneNumber)
                ->setWebsite($faker->url)
                ->setOpened(null)
            ;

            for($j = 0; $j < rand(1,5); $j++) {
                $place->addCategory($this->getReference("category-$j"));
            }

            foreach($placeImages as $placeImage) {
                $place->addImage($placeImage);
            }

            $manager->persist($place);

            $this->addReference("place-$i", $place);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}