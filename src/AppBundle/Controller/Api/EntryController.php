<?php

namespace Trakos\AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Trakos\AppBundle\Entity\Entry;
use Trakos\AppBundle\Form\EntryType;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EntryController extends FOSRestController
{

    protected function ensureEntryForLoggedInId(Entry $entry)
    {
        /* @var \Trakos\AppBundle\Structures\SteamAuthResult $result */
        $result = $this->get('session')->get('steam');
        if (!$result || !$result->success || $result->steamId != $entry->id) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Returns entry for given steam id
     * @Rest\Get(path="/api/entry/{csrfToken}/all")
     * @Rest\View
     * @ApiDoc(
     *  resource = true,
     *  output = "\Trakos\AppBundle\Entity\Entry[]",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails"
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
    public function queryEntriesAction(CsrfToken $csrfToken)
    {
        return array_values($this->getDoctrine()->getRepository('AppBundle:Entry')->findAll());
    }

    /**
     * Returns entry for given steam id
     * @Rest\Get(path="/api/entry/{csrfToken}/{id}")
     * @Rest\View
     * @ApiDoc(
     *  resource = true,
     *  output = "\Trakos\AppBundle\Entity\Entry",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails",
     *      404 = "Returned when entry with this id not found"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"},
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="entry id"}
     *  }
     * )
     *
     * @param CsrfToken $csrfToken
     * @param Entry     $entry
     *
     * @return JsonResponse
     */
    public function getEntryAction(CsrfToken $csrfToken, Entry $entry)
    {
        return $entry;
    }

    /**
     * Creates entry
     *
     * @Rest\Post(path="/api/entry/{csrfToken}")
     * @Rest\View
     * @ApiDoc(
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"}
     *  },
     *  input = {
     *      "class" = "\Trakos\AppBundle\Form\EntryType",
     *      "name" = "form"
     *  }
     * )
     *
     * @param CsrfToken $csrfToken
     *
     * @return JsonResponse
     */
    public function createEntryAction(CsrfToken $csrfToken)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $entry = new Entry();
        $this->get('trakos.rest_form_handler')->handleRestInput($request, new EntryType(), $entry, "post");
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * Updates entry
     *
     * @Rest\Post(path="/api/entry/{csrfToken}/{id}")
     * @Rest\View
     * @ApiDoc(
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails",
     *      404 = "Returned when entry with this id not found"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"},
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="entry id"}
     *  },
     *  input = {
     *      "class" = "\Trakos\AppBundle\Form\EntryType",
     *      "name" = "form"
     *  }
     * )
     *
     * @param CsrfToken $csrfToken
     *
     * @return JsonResponse
     */
    public function updateEntryAction(CsrfToken $csrfToken, Entry $entry)
    {
        $this->ensureEntryForLoggedInId($entry);
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $this->get('trakos.rest_form_handler')->handleRestInput($request, new EntryType(), $entry, "post");
        $this->ensureEntryForLoggedInId($entry);
        $this->getDoctrine()->getManager()->persist($entry);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * Removes entry with given id
     * @Rest\Delete(path="/api/entry/{csrfToken}/{id}")
     * @Rest\View
     * @ApiDoc(
     *  resource = true,
     *  output = "\Trakos\AppBundle\Entity\Entry",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails",
     *      404 = "Returned when entry with this id not found"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"},
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="entry id"}
     *  }
     * )
     *
     * @param CsrfToken $csrfToken
     * @param Entry     $entry
     *
     * @return JsonResponse
     */
    public function removeEntryAction(CsrfToken $csrfToken, Entry $entry)
    {
        $this->ensureEntryForLoggedInId($entry);
        $this->getDoctrine()->getManager()->remove($entry);
        $this->getDoctrine()->getManager()->flush();
    }
}