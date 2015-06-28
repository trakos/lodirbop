<?php

namespace Trakos\AppBundle\Tests\Utils;

use Nelmio\ApiDocBundle\Form\Extension\DescriptionFormTypeExtension;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ValidatorBuilderInterface;

/**
 * Adding validation extension.
 * We are not using symfony classes for that, because we are adding validator extension, which is not there normally.
 * Plus regular setUp would be much slower than static one.
 * @package Trakos\AppBundle\Tests\Utils
 */
abstract class EnabledValidationTypeTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidatorBuilderInterface
     */
    static protected $validator;
    /**
     * @var FormBuilder
     */
    static protected $builder;
    /**
     * @var EventDispatcher
     */
    static protected $dispatcher;
    /**
     * @var FormFactoryInterface
     */
    static protected $factory;

    /**
     * Let's do that just once, it can be shared between many tests.
     * It's more than 3x times faster than setUp every time.
     */
    static public function setUpBeforeClass()
    {
        self::$validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        self::$factory = Forms::createFormFactoryBuilder()
            ->addExtensions(static::getExtensions())
            ->addTypeExtension(new DescriptionFormTypeExtension())
            ->getFormFactory();
        $mocker = new \PHPUnit_Framework_MockObject_Generator();
        self::$dispatcher = $mocker->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        self::$builder = new FormBuilder(null, null, self::$dispatcher, self::$factory);

    }

    static public function tearDownAfterClass()
    {
        self::$validator = null;
        self::$builder = null;
        self::$dispatcher = null;
        self::$factory = null;
    }

    static protected function getExtensions()
    {
        return [
            new ValidatorExtension(self::$validator)
        ];
    }

}