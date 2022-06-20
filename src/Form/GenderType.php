<?php

namespace App\Form;

use App\Entity\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GenderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', TextType::class, [
            'label' => 'Genre :',
        ])
        ->add('createdAt', DateType::class, [
            'years' => range(date('1970'), date('Y')),
            'label' => 'Date de création:',
            'placeholder' => [
                'Année' => 'Year',
                'Mois' => 'Month',
                'Jour' => 'Day',
            ],
            'format' => 'dd MM yyyy',
            'input' => 'datetime_immutable',
        ])
        ->add('updatedAt', DateType::class, [
                'years' => range(date('Y'), date('Y')-10),
                'label' => 'Date de la mise à jour:',
                'placeholder' => [
                    'Année' => 'Year',
                    'Mois' => 'Month',
                    'Jour' => 'Day',
                ],
                'format' => 'dd MM yyyy',
                'input' => 'datetime_immutable',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gender::class,
        ]);
    }
}
