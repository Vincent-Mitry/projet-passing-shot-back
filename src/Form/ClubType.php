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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('logo', UrlType::class, [
                'label' => 'Logo du club',
                'attr' => [
                    'placeholder' => 'Champ optionnel'
                ],
                'required' => false,
            ])
            ->add('startingTime', TimeType::class, [
                'label' => 'Heure d\'ouverture',
                'placeholder' => [
                    'hour' => 'Heure', 
                    'minute' => 'Minutes', 
                ],
                'required' => true,
                'attr' => ['class' => 'col-4']
            ])
            ->add('endingTime', TimeType::class, [
                'label' => 'Heure de fermeture',
                'placeholder' => [
                    'hour' => 'Heure', 
                    'minute' => 'Minutes', 
                ],
                'required' => true,
                'attr' => ['class' => 'col-4']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            
            ->add('user', EntityType::class, [
                'placeholder' => '--- Sélectionner le propriétaire ---',
                'label' => 'Propriétaire',
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (EntityRepository $pr) {
                    return $pr->createQueryBuilder('u')
                              ->where('u.roles LIKE :role')
                              ->orderBy('u.roles', 'DESC')
                              ->setParameter('role', '%ROLE_SUPER_ADMIN%');
                },
                'required' => false,
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
