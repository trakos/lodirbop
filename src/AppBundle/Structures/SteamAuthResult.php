<?php

namespace Trakos\AppBundle\Structures;


class SteamAuthResult
{
    /**
     * @var boolean
     */
    public $success = false;

    /**
     * @var string|null
     */
    public $steamId = null;

    /**
     * @var string
     */
    public $errorMessage = null;


}