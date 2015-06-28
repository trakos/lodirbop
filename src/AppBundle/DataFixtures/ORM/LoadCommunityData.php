<?php

namespace Trakos\AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Trakos\AppBundle\Entity\Community;
use Trakos\AppBundle\Entity\Merc;

class LoadCommunityData implements FixtureInterface
{
    protected $communitiesList = [
        'cf' => 'crossfire.nu',
        'reddit' => '/r/dirtybomb'
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository('AppBundle:Community');
        foreach ($this->communitiesList as $icon => $communityName) {
            $found = $repository->findBy(['name' => $communityName]);

            if (empty($found)) {
                $game = new Community();
                $game->name = $communityName;
                $game->imagePath = 'images/community/' . $icon . '.png';
                $manager->persist($game);
            }
        }
        $manager->flush();
    }
}