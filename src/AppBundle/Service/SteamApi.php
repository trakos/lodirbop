<?php

namespace Trakos\AppBundle\Service;

use Doctrine\Common\Cache\CacheProvider;
use SteamApi\User;

class SteamApi
{
    /**
     * @var string
     */
    protected $steamApiKey;

    /**
     * @var CacheProvider
     */
    protected $cache;

    function __construct($steamApiKey, CacheProvider $cache)
    {
        $this->steamApiKey = $steamApiKey;
        $this->cache = $cache;
    }

    /**
     * @param $steamId
     * @return \SteamApi\Containers\Player|null
     */
    public function getSteamProfileData($steamId)
    {
        $cacheKey = 'steam-profile-' . $steamId;
        if (!$this->cache->contains($cacheKey)) {
            $user = new User($this->steamApiKey, $steamId);
            try {
                $player = $user->GetPlayerSummaries();
            } catch (\Exception $e) {
                $player = null;
            }
            if (!$player) {
                // I'd rather have null than empty array
                $player = null;
            }
            $this->cache->save($cacheKey, $player);
        }
        return $this->cache->fetch($cacheKey);
    }

}