<?php

namespace Trakos\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * HomeController
 * @package Trakos\AppBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * Action redirecting to homeAction with determined user locale
     * @Route("/", name="index")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute(
            'homepage',
            [ '_locale' => $request->getPreferredLanguage($this->container->getParameter('available_locales')) ]
        );
    }

    /**
     * Action rendering homepage
     * @Route("/{_locale}", name="homepage", requirements={"_locale" = "[a-zA-Z]{2}"})
     * @Template()
     * @return array
     */
    public function homeAction()
    {
        return [ ];
    }

    /**
     * Action rendering homepage
     * @Route("/{_locale}/steam-open-id", name="steamOpenId", requirements={"_locale" = "[a-zA-Z]{2}"})
     * @Template()
     * @return array
     */
    public function steamOpenIdAction(Request $request)
    {
        $result = $this->get('trakos.steam_login')->auth();
        $this->get('session')->set('steam', $result);

        return $this->redirectToRoute(
            'homepage',
            [ '_locale' => $request->getLocale() ]
        );
    }
}