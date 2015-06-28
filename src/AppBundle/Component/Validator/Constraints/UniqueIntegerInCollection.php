<?php

namespace Trakos\AppBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Checks whether there are no duplicates (in an integer collection)
 *
 * It's specifically for the integers to make sure we don't have for example '001' and '1'.
 * Conversion to integer occurs with base 10, so 0x08 or 07 will be treated as 0.
 *
 */
class UniqueIntegerInCollection extends Constraint
{
    public $message = 'Not all values are unique.';

}