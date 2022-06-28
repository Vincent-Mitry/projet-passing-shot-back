<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Court;
use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('startDatetime', DateTimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
            'label' => 'Date et heure de début',
            'years' => range(date('Y'), date('Y')+1),
            'attr' => ['class' => 'col-4']
            ],
            
        )
        ->add('endDatetime', DateTimeType::class, [
            'widget' => 'choice',
            'input' => 'datetime_immutable',
            'label' => 'Date et heure de fin',
            'years' => range(date('Y'), date('Y')+1),
            'attr' => ['class' => 'col-4']
            
        ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Actif' => 1,
                    'Inactif' => 0,
                ],
                'label' => 'Status de la réservation',
                'placeholder' => 'Sélectionner le status',
                'help' => 'choisir dans les choix suivants',
                
                
               
            ])
            
            ->add('countPlayers', ChoiceType::class, [
                'choices' => [
                    'Simple' => 2,
                    'Double' => 4,
                ],
                'placeholder' => 'Sélectionner le nombre de joueurs',
                'label' => 'Nombre de joueurs',
                'help' => 'choisir dans les choix suivants',
               
            ])

            ->add('court', EntityType::class, [
                'label' => 'Nom du terrain:',
                'class' => Court::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                'help' => 'Sélectionner un terrain.',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name');
                },
            ])
            ->add('user', EntityType::class, [
                'label' => 'Nom du membre :',
                'class' => User::class,
                'choice_label'=> 'lastname',   
                'multiple' => false,
                'expanded' => false,
                'help' => 'Sélectionner un membre.',
                'query_builder' => function (EntityRepository $ef) {
                    return $ef->createQueryBuilder('u')
                        ->orderBy('u.lastname', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
