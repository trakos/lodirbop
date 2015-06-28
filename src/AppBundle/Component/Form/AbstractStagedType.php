<?php

namespace Trakos\AppBundle\Component\Form;


use Symfony\Component\Form\AbstractType;
use Trakos\AppBundle\Component\Form\FormHandlingStage;

/**
 * We want to be able to apply validation on data before applying symfony transformations.
 *
 * (otherwise transformations would fail when applied to invalid data)
 * To achieve that, we create a special form type that will be used in two-staged form handling by method in see tag below
 * @see Trakos\Dirbos\AppBundle\Service\RestFormHandler::handleRestInput
 *
 * @package Trakos\AppBundle\Utils\Form
 */
abstract class AbstractStagedType extends AbstractType
{
    /**
     * Whether we are in stage 1 (validation) or stage 2 (transformation)

     * @var FormHandlingStage
     */
    protected $formHandlingStage;

    public function __construct()
    {
        $this->formHandlingStage = FormHandlingStage::Validation();
    }

    /**
     * Get whether we are in stage 1 (validation) or stage 2 (transformation)

     * @return FormHandlingStage
     */
    public function getFormHandlingStage()
    {
        return $this->formHandlingStage;
    }

    /**
     * Set whether we are in stage 1 (validation) or stage 2 (transformation)
     *
     * @param FormHandlingStage $formHandlingStage
     */
    public function setFormHandlingStage(FormHandlingStage $formHandlingStage)
    {
        $this->formHandlingStage = $formHandlingStage;
    }
}