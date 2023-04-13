<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse mail :',
                'attr' => [
                    'placeholder' => 'Votre adresse mail'
                ]
            ])

            ->add('plainPassword', PasswordType::class, [
                'label' => 'Votre mot de passe :',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'placeholder' => 'Votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => true,
                        'message' => "Votre mot de passe doit contenir au moins un chiffre."
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'match' => true,
                        'message' => "Votre mot de passe doit contenir au moins une majuscule."
                    ])
                ],
            ])
            ->add('plainPasswordVerif', PasswordType::class, [
                'label' => 'Confirmez votre mot de passe :',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'placeholder' => 'Votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => true,
                        'message' => "Votre mot de passe doit contenir au moins un chiffre."
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'match' => true,
                        'message' => "Votre mot de passe doit contenir au moins une majuscule."
                    ])
                ],
            ])
            ->add('pseudo', null, [
                'label' => 'Votre pseudo :',
                'attr' => [
                    'placeholder' => 'Pseudo'
                ],
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
