<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('name', TextType::class, [
                'label' => 'Nom :',
            ])
            ->add('logo', UrlType::class, [
                'label' => 'Logo du club :',
                'attr' => [
                    'placeholder' => 'Champ non obligatoire'
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'bref decriptif du club',
            ])
            ->add('createdAt', DateType::class, [
                'label' => 'Date d\'ouverture :',
                'placeholder' => [
                    'Année' => 'Year', 'Mois' => 'Month', 'Jour' => 'Day',
                ],
                'format' => 'dd MM yyyy',
                'input' => 'datetime_immutable',
            ])
            ->add('user', EntityType::class, [
                'label' => 'Nom du propriétaire :',
                'class' => User::class,
                'choice_label' => 'type',
                'multiple' => false,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                        // ->orderBy('u.roles', 'ASC')
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
