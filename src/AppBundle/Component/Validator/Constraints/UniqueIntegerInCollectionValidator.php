<?php

namespace Trakos\AppBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Checks whether there are no duplicates (in a basic types collection)
 *
 * It's specifically for the integers to make sure we don't have for example '001' and '1'.
 * Conversion to integer occurs with base 10, so 0x08 will be treated as 0, and 07 as 7.
 */
class UniqueIntegerInCollectionValidator extends ConstraintValidator
{

    /**
     * @inheritdoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueIntegerInCollection) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\UniqueIntegerInCollection');
        }

        // we are accepting empty value even if it's not an array
        // array_map with intval converts values to int
        if (!empty($value) && (!is_array($value) || count(array_unique(array_map('intval', $value))) != count($value))) {
            $this->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}