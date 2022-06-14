<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', UrlType::class, [
                'label' => 'Photo :',
                'attr' => [
                    'placeholder' => 'Champ non obligatoire'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :'
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance :',
                'placeholder' => [
                    'Année' => 'Year', 'Mois' => 'Month', 'Jour' => 'Day',
                ],
                'format' => 'dd MM yyyy',
                'input' => 'datetime_immutable',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail :',
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre :',
                'choices' => [
                    'Femme' => '1',
                    'Homme' => '2',
                    'Neutre' => '3',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Niveau :',
                'choices' => [
                    'Débutant' => '1',
                    'Intermédiaire' => '2',
                    'Confirmé' => '3',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone :',
                'constraints' => [
                    new NotBlank(),
                    // Regex pour numéro de téléphone (10 chiffres)
                    new Regex("/^(?=.*[0-9]).{10,10}$/")
                ],
                'attr' => [
                    'placeholder' => '0000000000'
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle :',
                'choices' => [
                    'Membre' => 'ROLE_USER',
                    'Gérant' => 'ROLE_ADMIN',
                    'Propriétaire' => 'ROLE_SUPER_ADMIN',
                ],

                'multiple' => false,
                'expanded' => true,
	        ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // Le user (qui est l'entité mappée sur le form) se trouve là
                $user = $event->getData();
                // Le form, pour continuer de travailler avec (car par accès aux variables en dehors de la fonction anonyme)
                $form = $event->getForm();

                // Add or Edit ?
                // Un nouveau User n'a pas d'id !
                if ($user->getId() === null) {
                    // Add (new)
                    $form->add('password', PasswordType::class, [
                        'label' => 'Mot de passe :',
                        'constraints' => [
                            new NotBlank(),
                            // REgex pour le mot de passe
                            new Regex("/^(?=.*[0-9])(?=.*[a-z])(?=.*['_', '-' , '|', '%', '&', '*', '=', '@', '$']).{6,}$/")
                        ],
                        'help' => 'Au moins 6 caractères,
                            au moins une majuscule,
                            un chiffre
                            et un caractère spécial parmi _, -, |, %, &, *, =, @, $'
                    ]);
                } else {
                    // Edit
                    $form->add('password', PasswordType::class, [
                        // Pour l'edit
                        'label' => 'Mot de passe :',
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé'
                        ],
                        // @link https://symfony.com/doc/current/reference/forms/types/text.html#mapped
                        // Ce champ de ne sera pas mappé sur l'entité automatiquement
                        // la propriété $password de $user ne sera pas modifiée par le traitement du form
                        'mapped' => false,
                    ]);
                }
            });

            

        // Ajout d'un Data Transformer
        // pour convertir la chaine choisie en un tableau
        // qui contient cette chaine et vice-versa
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            // De l'Entité vers le Form (affiche form)
            function ($rolesAsArray) {
                // transform the array to a string
                return implode(', ', $rolesAsArray);
            },
            // Du Form vers l'Entité (traite form)
            function ($rolesAsString) {
                // transform the string back to an array
                return explode(', ', $rolesAsString);
            }
        ));
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
