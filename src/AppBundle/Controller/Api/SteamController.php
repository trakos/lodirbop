<?php

namespace Trakos\AppBundle\Controller\Api;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SteamController extends FOSRestController
{

    /**
     * Returns steam data for logged in steamId
     * @Rest\Get(path="/api/steam-profile/{csrfToken}")
     * @Rest\View
     * @ApiDoc(
     *  output = "\SteamController\Containers\Player",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails",
     *      404 = "Not logged in"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"}
     *  }
     * )
     *
     * @param CsrfToken $csrfToken
     *
     * @return JsonResponse
     */
    public function getSteamProfileAction(CsrfToken $csrfToken)
    {
        /* @var \Trakos\AppBundle\Structures\SteamAuthResult $result */
        $result = $this->get('session')->get('steam');
        if (!$result || !$result->success) {
            throw new NotFoundHttpException();
        }
        return $this->get('trakos.steam_api')->getSteamProfileData($result->steamId);
    }

    /**
     * Logout from steam (i.e. forget about steam open id login)
     *
     * @Rest\Get(path="/api/steam-logout/{csrfToken}")
     * @Rest\View
     * @ApiDoc(
     *  statusCodes = {
     *      200 = "Returned when successful"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"}
     *  }
     * )
     *
     * @param CsrfToken $csrfToken
     *
     * @return JsonResponse
     */
    public function steamLogoutAction(CsrfToken $csrfToken)
    {
        $this->get('session')->remove('steam');
    }

}