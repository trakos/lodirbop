<?php

namespace Trakos\AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Trakos\AppBundle\Entity\Merc;

class LoadMercData implements FixtureInterface
{
    protected $mercList = [
        'arty',
        'aura',
        'bushwhacker',
        'fletcher',
        'fragger',
        'kira',
        'nader',
        'proxy',
        'rhino',
        'sawbonez',
        'skyhammer',
        'sparks',
        'vassili',
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository('AppBundle:Merc');
        foreach ($this->mercList as $mercName) {
            $found = $repository->findBy(array('name' => $mercName));

            if (empty($found)) {
                $merc = new Merc();
                $merc->name = ucfirst($mercName);
                $merc->imagePath = 'images/merc/' . $mercName . '.png';
                $manager->persist($merc);
            }
        }
        $manager->flush();
    }
}