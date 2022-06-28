<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Court;
use App\Entity\Surface;
use Doctrine\ORM\EntityManager;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class CourtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('surface', EntityType::class, [
                'label' => 'Surface :',
                'class' => Surface::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'help' => 'Sélectionner une surface.',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
            ])
            ->add('indoor', CheckboxType::class, [
                'label' => 'Couvert',
                'attr' => ['class' => 'form-row p-0'],
                'help' => 'Cocher si le terrain est couvert.',
                'required' => false,
            ])
            ->add('lightning', CheckboxType::class, [
                'label' => 'Éclairé',
                'attr' => ['class' => 'form-row p-0'],
                'help' => 'Cocher si le terrain est éclairé.',
                'required' => false,
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Heure d\'ouverture :',
                'placeholder' => [
                    'hour' => 'Heure', 
                    'minute' => 'Minutes', 
                ],
                'attr' => ['class' => 'col-4']
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'Heure de fermeture',
                'placeholder' => [
                    'hour' => 'Heure', 
                    'minute' => 'Minutes', 
                ],
                'attr' => ['class' => 'col-4']
            ])
            ->add('picture', UrlType::class, [
                'label' => 'Photo',
                'attr' => [
                    'placeholder' => 'Champ optionnel'
                ],
                'required' => false,
            ])
            ->add('detailed_map', UrlType::class, [
                'label' => 'Plan',
                'attr' => [
                    'placeholder' => 'Champ optionnel'
                ],
                'required' => false,
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug'
            ])
            ->add('renovatedAt', DateType::class, [
                'years' => range(date('Y'), date('Y')-10),
                'label' => 'Date de rénovation :',
                'required' => false,
                'empty_data' => null,
                'placeholder' => [
                    'year' => 'Année', 
                    'month' => 'Mois', 
                    'day' => 'Jour',
                ],
                'format' => 'dd MM yyyy',
                'input' => 'datetime_immutable',
                'help' => 'Champ optionnel',
                'attr' => ['class' => 'col-4']
            ])
        
            ->add('club', EntityType::class, [
                'label' => 'Club',
                'class' => Club::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'help' => 'Sélectionner un club.',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ]);
            
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Court::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
