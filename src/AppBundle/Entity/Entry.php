<?php

namespace Trakos\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SteamApi\Containers\Player;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="entry")
 */
class Entry
{
    const TIMEZONE_EUROPEAN = 'european';
    const TIMEZONE_AMERICAN = 'american';

    const GAME_MODE_COMPETITIVE = 'competitive';
    const GAME_MODE_PUBLIC = 'public';

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     * @ORM\Id
     * @JMS\Type("string")
     *
     * @var string
     */
    public $id;

    /**
     * @ORM\Column(type="integer", nullable=true, length=100)
     * @var int
     */
    public $age;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    public $description;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('european', 'american') NOT NULL")
     * @var string
     */
    public $timezone;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('competitive', 'public') NOT NULL")
     * @var string
     */
    public $preferredGameMode;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    public $isUsingVoiceChat;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Exclude
     * @var \DateTime
     */
    public $addedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Exclude
     * @var \DateTime
     */
    public $editedAt;


    /**
     * @ORM\Column(type="text", length=39)
     * @JMS\Exclude
     * @var string
     */
    public $addedFromIp;

    /**
     * @ORM\Column(type="text", nullable=true, length=39)
     * @JMS\Exclude
     * @var string
     */
    public $editedFromIp;

    /**
     * @ORM\ManyToMany(targetEntity="Game")
     * @ORM\JoinTable(name="entry_games",
     *      joinColumns={@ORM\JoinColumn(name="entry_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")}
     *      )
     * @var Game[]
     **/
    public $games;

    /**
     * @ORM\ManyToMany(targetEntity="Merc")
     * @ORM\JoinTable(name="entry_mercs",
     *      joinColumns={@ORM\JoinColumn(name="entry_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="merc_id", referencedColumnName="id")}
     *      )
     * @var Merc[]
     **/
    public $mercs;

    /**
     * @ORM\ManyToMany(targetEntity="Community")
     * @ORM\JoinTable(name="entry_communities",
     *      joinColumns={@ORM\JoinColumn(name="entry_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="community_id", referencedColumnName="id")}
     *      )
     * @var Community[]
     **/
    public $communities;

    /**
     * @var Player|null
     */
    protected $steamProfileData;

    /**
     * @return Player|null
     */
    public function getSteamProfileData()
    {
        return $this->steamProfileData;
    }

    public function setSteamProfileData($steamProfileData)
    {
        $this->steamProfileData = $steamProfileData;
    }

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->mercs = new ArrayCollection();
        $this->communities = new ArrayCollection();
    }

}