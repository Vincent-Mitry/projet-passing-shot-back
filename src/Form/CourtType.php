<?php

namespace App\Form;

use App\Entity\Court;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class CourtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom :'
        ])
            ->add('surface')
            ->add('lightning')
            ->add('type')
            ->add('startTime', TimeType::class,[
                'label' => 'Heure d\'ouverture :'
            ])
            ->add('endTime', TimeType::class,[
                'label' => 'Heure de fermeture :'
            ])
            ->add('picture', UrlType::class, [
                'label' => 'Photo :',
                'attr' => [
                    'placeholder' => 'Champ non obligatoire'
                ],
            ])
            ->add('detailled_map', UrlType::class, [
                'label' => 'Plan :',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug :'
            ])
            ->add('renovatedAt', DateType::class, [
                'label' => 'Date de rénovation :',
                'placeholder' => [
                    'Année' => 'Year', 'Mois' => 'Month', 'Jour' => 'Day',
                ],
                'format' => 'dd MM yyyy',
                'input' => 'datetime_immutable',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Court::class,
        ]);
    }
}
