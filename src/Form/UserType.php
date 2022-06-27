<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Gender;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\RequestStack;

class UserType extends AbstractType
{
    protected $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
        $this->rolesCallbackTransformer = new CallbackTransformer(
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
        );
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', UrlType::class, [
                'label' => 'Photo',
                'attr' => [
                    'placeholder' => 'Champ optionnel'
                ],
                'required' => false,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'format' => 'dd MM yyyy',
                'input' => 'datetime_immutable',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
            ])
            ->add('gender', EntityType::class, [
                'label' => 'Genre',
                'class' => Gender::class,
                'choice_label' => 'type',
                'multiple' => false,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.type', 'ASC');
                }
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Niveau',
                'choices' => [
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Confirmé' => 'Confirmé',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => '0606060606'
                ],
            ])
            // Add Choice Type For Roles depending on route name
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                
                $form = $event->getForm();

                $routeName = $this->request->getCurrentRequest()->attributes->get('_route');

                // New User Member Form
                if ($routeName === "app_user_member_new") {
                    $form->add('roles', ChoiceType::class, [
                        'label' => 'Rôles',
                        'choices' => [
                            'Membre' => 'ROLE_MEMBER',
                        ],
                        'multiple' => false,
                        'expanded' => true,
                        'model_transformer' => $this->rolesCallbackTransformer,
                    ]);
                }
                // New and Edit Back-Office User Form  
                elseif($routeName === "app_user_back-office_new" || $routeName === "app_user_back-office_edit") {
                    $form->add('roles', ChoiceType::class, [
                        'label' => 'Rôles',
                        'choices' => [
                            'Gérant' => 'ROLE_ADMIN',
                            'Propriétaire' => 'ROLE_SUPER_ADMIN',
                        ],
                        'help' => 'Sélectionner un rôle.',
                        'multiple' => false,
                        'expanded' => true,
                        'model_transformer' => $this->rolesCallbackTransformer,
                    ]);
                }
            })
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
                        'label' => 'Mot de passe',
                        'help' => 'Au moins 6 caractères,
                            un chiffre
                            et un caractère spécial parmi _, -, |, %, &, *, =, @, $'
                    ]);
                } else {
                    // Edit
                    $form->add('password', PasswordType::class, [
                        // Pour l'edit
                        'label' => 'Mot de passe',
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
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
