<?php

namespace App\Form;

use App\Entity\Court;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('surface')
            ->add('lightning')
            ->add('type')
            ->add('startTime')
            ->add('endTime')
            ->add('picture')
            ->add('detailled_map')
            ->add('ratingAvg')
            ->add('slug')
            ->add('renovatedAt')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('club')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Court::class,
        ]);
    }
}
