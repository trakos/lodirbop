<?php

namespace Trakos\AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller for generating angular templates
 *
 * @package Trakos\AppBundle\Controller
 *
 * @Cache(expires="+7 days")
 */
class TemplatesController extends Controller
{
    /**
     * @Route("/{_locale}/templates/main", name="templates_main")
     * @Template()
     *
     * @return array
     */
    public function mainAction()
    {
        return [];
    }

    /**
     * @Route("/{_locale}/templates/steam-login", name="templates_steamLogin")
     * @Template()
     *
     * @return array
     */
    public function steamLoginAction()
    {
        return [
            'steamLoginUrl' => $this->get('trakos.steam_login')->getAuthUrl()
        ];
    }

    /**
     * @Route("/{_locale}/templates/edit-entry", name="templates_editEntry")
     * @Template()
     *
     * @return array
     */
    public function editEntryAction()
    {
        return [ ];
    }

    /**
     * @Route("/{_locale}/templates/player-list", name="templates_playerList")
     * @Template()
     *
     * @return array
     */
    public function playerListAction()
    {
        return [ ];
    }
}