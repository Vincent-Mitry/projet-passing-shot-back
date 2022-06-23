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
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('name', TextType::class, [
                'label' => 'Nom :',
                'required' => true,
            ])
            ->add('logo', UrlType::class, [
                'label' => 'Logo du club :',
                'attr' => [
                    'placeholder' => 'Champ non obligatoire'
                ],
            ])
            ->add('startingTime', TimeType::class, [
                'label' => 'Heure d\'ouverture :',
                'required' => true,
            ])
            ->add('endingTime', TimeType::class, [
                'label' => 'Heure de fermeture :',
                'required' => true,
                ])
            ->add('description', TextType::class, [
                'label' => 'bref decriptif du club',
                'required' => true,
            ])
            
            ->add('user', EntityType::class, [
                'label' => 'Nom du propriétaire :',
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => false,
                'expanded' => true,
                'query_builder' => function (EntityRepository $pr) {
                    return $pr->createQueryBuilder('u')
                              ->orderBy('u.roles', 'DESC');
                }])   
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
