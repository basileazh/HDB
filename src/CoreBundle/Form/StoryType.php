<?php

namespace CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use CoreBundle\Entity\Boug;
use CoreBundle\Form\BougStoryReadAccessType;

class StoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('title',      TextType::class, ['label' => 'Titre'])
            ->add('content',    TextareaType::class, ['label' => 'Contenu'])
            ->add('bougStoryReadAccess', CollectionType::class, [
                    'entry_type' => BougStoryReadAccessType::class, 
                    'entry_options' => [
                        'data_class' => null,
                    ],
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'label' => 'Accès en lecture',
                ])
            ->add('save', SubmitType::class, ['label' => 'Create Story']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CoreBundle\Entity\Story'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'corebundle_story';
    }


}
