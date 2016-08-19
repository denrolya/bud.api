<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Event;
use AppBundle\Entity\File;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEventData implements FixtureInterface, ContainerAwareInterface
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
            $eventImages = [];
            for($j = 0; $j <= rand(2,5); $j++) {
                $files = glob($this->container->getParameter('uploads_dir') . '/fixtures/*');
                $randomIndex = array_rand($files);
                $randomFile = new \Symfony\Component\HttpFoundation\File\File($files[$randomIndex]);

                $eventImage = new File();
                $eventImage
                    ->setName($randomFile->getFilename())
                    ->setSize($randomFile->getSize())
                    ->setAbsolutePath($randomFile->getRealPath())
                    ->setUri('http://bud.api/uploads/fixtures/' . $randomFile->getFilename());
                $manager->persist($eventImage);

                array_push($eventImages, $eventImage);
            }

            $dateFrom = $faker->dateTimeBetween('+0 months', '+3 months');
            $dateTo = $faker->dateTimeBetween($dateFrom, '+4 months');
            $event = new Event();
            $event
                ->setTitle($faker->sentence())
                ->setShortDescription($faker->sentences(3, true))
                ->setDescriptionBlock1("<p>".implode('</p><p>', $faker->paragraphs(3))."</p>")
                ->setDescriptionBlock2("<p>".implode('</p><p>', $faker->paragraphs(3))."</p>")
                ->setDateFrom($dateFrom)
                ->setDateTo($dateTo)
                ->setLocation($faker->word);

            foreach($eventImages as $eventImage) {
                $event->addImage($eventImage);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }
}