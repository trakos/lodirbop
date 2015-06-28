<?php

namespace Trakos\AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Trakos\AppBundle\Entity\Game;
use Trakos\AppBundle\Entity\Merc;

class LoadGameData implements FixtureInterface
{
    protected $gameList = [
        'rtcw' => 'Return to Castle Wolfenstein',
        'wet' => 'Wolfenstein: Enemy Territory',
        'qw' => 'Enemy Territory: Quake Wars',
        'brink' => 'Brink',
        'bf' => 'Battlefield series',
        'csgo' => 'Counter Strike: Global Offensive',
        'ql' => 'Quake Live',
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $repository = $manager->getRepository('AppBundle:Game');
        foreach ($this->gameList as $icon => $gameName) {
            $found = $repository->findBy(['name' => $gameName]);

            if (empty($found)) {
                $game = new Game();
                $game->name = $gameName;
                $game->imagePath = 'images/game/' . $icon . '.png';
                $manager->persist($game);
            }
        }
        $manager->flush();
    }
}