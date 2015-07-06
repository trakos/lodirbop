<?php

namespace Trakos\AppBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsJson
 *
 * @Annotation
 */
class IsJson extends Constraint
{
    public $message = 'Not a json.';

}