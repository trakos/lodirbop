<?php

namespace Trakos\AppBundle\Tests\Utils;

use Nelmio\ApiDocBundle\Form\Extension\DescriptionFormTypeExtension;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Adding mocked (disabled) validation.
 *
 * @package Trakos\AppBundle\Tests\Utils
 */
abstract class MockedValidationTypeTestCase extends TypeTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $validator = $this->getMock('\Symfony\Component\Validator\Validator\ValidatorInterface');
        $validator->expects($this->any())->method('validate')->will($this->returnValue(new ConstraintViolationList()));
        /** @noinspection PhpParamsInspection */
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension(
                new FormTypeValidatorExtension(
                    $validator
                )
            )
            ->addTypeExtension(
                new DescriptionFormTypeExtension()
            )
            ->addTypeGuesser(
                $this->getMockBuilder(
                    'Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser'
                )
                ->disableOriginalConstructor()
                ->getMock()
            )
            ->getFormFactory();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

}