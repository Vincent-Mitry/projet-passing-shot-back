<?php

namespace App\Form;

use App\Entity\BlockedCourt;
use App\Entity\Court;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockedCourtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDatetime', DateTimeType::class, [
                'label' => 'Date et heure de début',
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                    'hour' => 'Heure',
                    'minute' => 'Minutes'
                ],
                'input' => 'datetime_immutable',
                'years' => range(date('Y')+1, date('Y')),
            ])
            ->add('endDatetime', DateTimeType::class, [
                'label' => 'Date et heure de fin',
                'placeholder' => [
                    'year' => 'Année',
                    'month' => 'Mois',
                    'day' => 'Jour',
                    'hour' => 'Heure',
                    'minute' => 'Minutes'
                ],
                'input' => 'datetime_immutable',
                'years' => range(date('Y')+1, date('Y')),
            ])
            ->add('blockedReason', TextType::class, [
                'label' => 'Raison du blocage'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlockedCourt::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
