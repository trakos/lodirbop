<?php

namespace Trakos\AppBundle\Component\Form;

use MabeEnum\Enum;

/**
 * Enum FormHandlingStage
 * @see Trakos\Dirbos\AppBundle\Utils\Form\AbstractStagedType
 *
 * Class MabeEnum\Enum adds magic static methods (returning always the same instance for given constant):
 * @method static FormHandlingStage Validation() returns always the same instance of self with value Validation
 * @method static FormHandlingStage Transformation() returns always the same instance of self with value Transformation
 *
 * It was meant to be just a SplEnum initially, but when I was on windows spl types pear dll was crashing my php randomly
 *
 * @package Trakos\AppBundle\Utils\Form
 */
class FormHandlingStage extends Enum
{
    const Validation = 1;
    const Transformation = 2;
}