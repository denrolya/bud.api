<?php

namespace LandingBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use LandingBundle\Entity\PotentialClient;

class PotentialClientListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if (!$entity instanceof PotentialClient) {
            return;
        }

        // Send email to $entity->getEmail()
    }
}