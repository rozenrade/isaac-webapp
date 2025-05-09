<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class SignUpForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Username',
                    'required' => false,
                    'constraints' => [new Assert\NotBlank()]
                ]
            )

            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'xyz@email.xyz'],
                'required' => false,
                'constraints' => [new Assert\NotBlank(), new Assert\Email(['message' => 'Email incorrect'])]
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe ne correspondent pas',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit comporter au moins 4 caractères',
                        'max' => 16,
                        'maxMessage' => 'Votre mot de passe ne peut pas dépasser 16 caractères',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,16}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
                    ])
                ]
            ])
            

            
            ->add('cgu', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les Conditions Générales d\'Utilisation',
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'Vous devez accepter les Conditions Générales d\'Utilisation.',
                    ]),
                ],
            ])

            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
