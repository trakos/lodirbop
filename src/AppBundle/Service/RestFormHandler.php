<?php


namespace Trakos\AppBundle\Service;

use Trakos\AppBundle\Component\Form\AbstractStagedType;
use Trakos\AppBundle\Component\Form\FormHandlingStage;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class for handling forms in REST api (throws http exception on validation fail)
 *
 * Can handle two-staged forms (types extending AbstractStagedType)
 * @see AbstractStagedType
 *
 * @package Trakos\AppBundle\Service
 */
class RestFormHandler
{

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Constructor
     *
     * @param FormFactoryInterface $formFactory
     * @param TranslatorInterface $translator
     */
    public function __construct(FormFactoryInterface $formFactory, TranslatorInterface $translator)
    {
        $this->formFactory = $formFactory;
        $this->translator = $translator;
    }

    /**
     * Will throw HttpException when form validation fails (which will be rendered as a json thanks to FosRestBundle).
     *
     * It handles two-staged handling when $type extends AbstractStagedType
     * @see AbstractStagedType
     *
     * @param Request $request
     * @param FormTypeInterface|string|AbstractStagedType $type
     * @param $object
     * @param string $method
     *
     * @throws HttpException
     */
    public function handleRestInput(Request $request, $type, $object, $method = 'post')
    {
        if ($type instanceof AbstractStagedType) {
            // two stages (validation and transformation)
            $type->setFormHandlingStage(FormHandlingStage::Validation());
            $this->handleRequest($request, $type, null, $method);
            $type->setFormHandlingStage(FormHandlingStage::Transformation());
        }
        // regular one-stage handling or second stage of handling
        $this->handleRequest($request, $type, $object, $method);
    }

    protected function handleRequest(Request $request, $type, $object, $method = 'post')
    {
        $form = $this->formFactory->createNamed('form', $type, $object, ['method' => $method]);
        $form->handleRequest($request);
        $jsonData = json_decode($request->getContent(), true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new BadRequestHttpException($this->translator->trans("Payload data isn't a json"));
        }
        $form->submit($jsonData);
        if (!$form->isSubmitted()) {
            throw new BadRequestHttpException($this->translator->trans("No required parameters were sent"));
        }
        if (!$form->isValid()) {
            throw new BadRequestHttpException($this->translator->trans("Input validation failed: {{ errors }}",
                ['{{ errors }}' => $this->getErrors($form)])
            );
        }

    }

    /**
     * Just a basic toString actually.
     *
     * @param FormInterface $form
     * @return string
     */
    protected function getErrors(FormInterface $form)
    {
        $string = "";
        foreach ($form->getErrors(true, true) as $formError) {
            if ($formError->getOrigin()) {
                $string .= $formError->getOrigin()->getName();
                $string .= ": ";
            }
            $string .= $formError->getMessage() . ";";
        }
        return $string;
    }
}