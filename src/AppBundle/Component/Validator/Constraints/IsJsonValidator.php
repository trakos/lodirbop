<?php

namespace Trakos\AppBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsJsonValidator extends ConstraintValidator
{

    /**
     * @inheritdoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsJson) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\IsJson');
        }

        json_decode($value);
        if (json_last_error() != JSON_ERROR_NONE) {
            $this->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}