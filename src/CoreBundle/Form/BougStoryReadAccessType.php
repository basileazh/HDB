<?php

namespace CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;

use CoreBundle\Entity\Boug;
use CoreBundle\Entity\Story;

class BougStoryReadAccessType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    ]
                ])
            ->add('boug', EntityType::class, [
                'class' => 'CoreBundle:Boug',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('boug')
                    ->orderBy('boug.username', 'ASC');
                    },
                'choice_label' => 'username',
                ])
            // ->add('story', EntityType::class, [
            //     'class' => 'CoreBundle:Story',
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('story')
            //         ->orderBy('story.title', 'ASC');
            //         },
            //     'choice_label' => 'username',
            //     ])
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\BougStoryReadAccess'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'corebundle_bougstoryreadaccess';
    }


}
