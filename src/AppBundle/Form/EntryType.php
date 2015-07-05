<?php

namespace Trakos\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Trakos\AppBundle\Component\Form\AbstractStagedType;
use Trakos\AppBundle\Component\Form\FormHandlingStage;
use Trakos\AppBundle\Component\Validator\Constraints\UniqueIntegerInCollection;
use Trakos\AppBundle\Entity\Entry;

class EntryType extends AbstractStagedType
{
    protected function getIntegerCollectionValidationOptions()
    {
        return [
            'type' => 'integer',
            // don't force unchanged collection size
            'allow_add' => true,
            'allow_delete' => true,
            // don't allow empty numbers
            'delete_empty' => false,
            'cascade_validation' => true,
            'constraints' => [
                new Type([
                    'type' => 'array'
                ]),
                new UniqueIntegerInCollection(),
            ],
            'options' => [
                'constraints' => [
                    new NotBlank(),
                    new Type([
                        'type' => 'int'
                    ])
                ]
            ]
        ];
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text', [ 'required' => true ])
            ->add('age', 'integer')
            ->add('description', 'text', ['constraints' => [ new Length(['max' => 500])]])
            ->add(
                'timezone',
                'choice',
                [ 'required' => true, 'choices' => [ Entry::TIMEZONE_AMERICAN => Entry::TIMEZONE_AMERICAN, Entry::TIMEZONE_EUROPEAN => Entry::TIMEZONE_EUROPEAN ] ]
            )
            ->add(
                'preferredGameMode',
                'choice',
                [ 'required' => true, 'choices' => [ Entry::GAME_MODE_COMPETITIVE => Entry::GAME_MODE_COMPETITIVE, Entry::GAME_MODE_PUBLIC => Entry::GAME_MODE_PUBLIC ] ]
            )
            ->add('isUsingVoiceChat', 'checkbox')
            ->add(
                'games',
                $this->getFormHandlingStage() == FormHandlingStage::Validation() ? 'collection' : null,
                $this->getFormHandlingStage() == FormHandlingStage::Validation() ? $this->getIntegerCollectionValidationOptions() : [ ]
            )
            ->add(
                'mercs',
                $this->getFormHandlingStage() == FormHandlingStage::Validation() ? 'collection' : null,
                $this->getFormHandlingStage() == FormHandlingStage::Validation() ? $this->getIntegerCollectionValidationOptions() : [ ]
            )
            ->add(
                'communities',
                $this->getFormHandlingStage() == FormHandlingStage::Validation() ? 'collection' : null,
                $this->getFormHandlingStage() == FormHandlingStage::Validation() ? $this->getIntegerCollectionValidationOptions() : [ ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Trakos\AppBundle\Entity\Entry',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'form';
    }
}
