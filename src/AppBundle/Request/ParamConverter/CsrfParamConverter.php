<?php

namespace Trakos\AppBundle\Request\ParamConverter;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CsrfParamConverter implements ParamConverterInterface
{
    /**
     * @var CsrfTokenManagerInterface
     */
    protected $csrfManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    function __construct(CsrfTokenManagerInterface $csrfManager, TranslatorInterface $translator)
    {
        $this->csrfManager = $csrfManager;
        $this->translator = $translator;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request        $request       The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $csrfTokenValue = $request->attributes->get($configuration->getName());
        $token = new CsrfToken('form', $csrfTokenValue);
        if (!$this->csrfManager->isTokenValid($token)) {
            throw new BadRequestHttpException($this->translator->trans("Csrf token is invalid"));
        }
        $request->attributes->set($configuration->getName(), new CsrfToken('form', 'test'));
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() == 'Symfony\Component\Security\Csrf\CsrfToken';
    }
}