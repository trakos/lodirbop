<?php

namespace Trakos\AppBundle\Controller\Api;

use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Trakos\AppBundle\Entity\Entry;
use Trakos\AppBundle\Form\EntryType;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Trakos\AppBundle\Component\Validator\Constraints\IsJson;

class EntryController extends FOSRestController
{

    /**
     * @return \Doctrine\ORM\EntityRepository
     * @throws \Exception
     */
    protected function getRepository()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Entry');
        if ((!$repository instanceof EntityRepository)) {
            throw new \Exception("Repository is not of type EntityRepository!");
        }
        return $repository;
    }

    protected function ensureEntryForLoggedInId(Entry $entry)
    {
        /* @var \Trakos\AppBundle\Structures\SteamAuthResult $result */
        $result = $this->get('session')->get('steam');
        if (!$result || !$result->success || $result->steamId != $entry->id) {
            throw new NotFoundHttpException();
        }
    }

    protected $allowedFilters = ['isUsingVoiceChat', 'preferredGameMode', 'timezone'];

    protected function prepareFilters($filters)
    {
        $preparedFilters = [];
        foreach ($filters as $name => $value) {
            if ($value && !is_array($value) && !is_object($value) && in_array($name, $this->allowedFilters)) {
                $preparedFilters[$name] = $value;
            }
        }
        return $preparedFilters;
    }

    /**
     * Returns entry for given steam id
     * @Rest\Get(path="/api/entry/{csrfToken}/all")
     * @Rest\View
     * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing pages.")
     * @Rest\QueryParam(name="limit", requirements="\d+", default="50", description="How many pages to return.")
     * @Rest\QueryParam(name="filters", requirements=@IsJson, description="Json with filters")
     * @ApiDoc(
     *  resource = true,
     *  output = "\Trakos\AppBundle\Entity\Entry[]",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      400 = "Returned when validation fails"
     *  },
     *  parameters = {
     *      {"name"="csrfToken", "dataType"="string", "required"=true, "description"="csrf token value"},
     *      {"name"="offset", "dataType"="int"},
     *      {"name"="limit", "dataType"="int"}
     *  }
     * )
     *
     * @param CsrfToken             $csrfToken
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function queryEntriesAction(CsrfToken $csrfToken, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = max($offset, 0);
        $limit = $paramFetcher->get('limit');
        $limit = min(100, max(5, $limit));
        $filters = $paramFetcher->get('filters');
        $filters = $filters ? $this->prepareFilters(json_decode($filters, true)) : [];
        $count = $this->getRepository()->createQueryBuilder('e')->select('count(e)')->getQuery()->getSingleScalarResult();
        $data = $this->getRepository()->findBy($this->prepareFilters($filters), [], $limit, $offset);
        return $this
            ->view($data)
            ->setHeader('X-COUNT', $count);
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
        $entry->addedAt = new \DateTime();
        $entry->addedFromIp = $request->getClientIp();
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
        $entry->editedAt = new \DateTime();
        $entry->editedFromIp = $request->getClientIp();
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