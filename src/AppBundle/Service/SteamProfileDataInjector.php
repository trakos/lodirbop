<?php

namespace Trakos\AppBundle\Service;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Trakos\AppBundle\Entity\Entry;

class SteamProfileDataInjector
{
    /**
     * @var SteamApi
     */
    protected $steamApi;

    function __construct(SteamApi $steamApi)
    {
        $this->steamApi = $steamApi;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Entry) {
            $entity->setSteamProfileData($this->steamApi->getSteamProfileData($entity->id));
        }
    }
}