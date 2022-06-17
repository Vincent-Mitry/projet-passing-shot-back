<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Court;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                'label' => 'Nom :',
            ])
            ->add('surface', ChoiceType::class, [
                'label' => 'Surface :',
                'choices' => [
                    'Terre battue' => 1,
                    'Greenset' => 2
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => true,
            ])
            
            ->add('type', CheckboxType::class, [
                'label' => 'Couvert',
                'attr' => ['class' => 'form-row p-0'],
                'required' => false,
                ])
            ->add('lightning', CheckboxType::class, [
            'label' => 'Éclairé',
            'attr' => ['class' => 'form-row p-0'],
            'required' => false,
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Heure d\'ouverture :'
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'Heure de fermeture :',
            ])
            ->add('picture', UrlType::class, [
                'label' => 'Photo :',
                'attr' => [
                    'placeholder' => 'Champ non obligatoire'
                ],
            ])
            ->add('detailled_map', UrlType::class, [
                'label' => 'Plan :',
                'attr' => [
                    'placeholder' => 'Champ non obligatoire'
                ],
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
            ]);
            
            //->add('club', EntityType::class, [
            //    'label' => 'Club :',
            //    'class' => Club::class,
            //    'choice_label' => 'name',
            //    'multiple' => false,
            //    'expanded' => true,
            //    'help' => 'Sélectionner au moins un club.',
            //    'query_builder' => function (EntityRepository $er) {
            //        return $er->createQueryBuilder('c')
            //            ->orderBy('c.name', 'ASC');
            //    },
            //    'data' => $this->container->get('doctrine.orm.entity_manager')->getReference(Club::class, 1)
            //])
            // renovatedAt just for edit
            
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Court::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
