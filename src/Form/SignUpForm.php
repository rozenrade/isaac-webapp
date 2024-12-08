<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
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
            ->add('username', TextType::class, 
                ['label' => 'Username', 
                    'constraints' => [new Assert\NotBlank()]
                ])

            ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'attr' => ['placeholder' => 'xyz@email.xyz'],
                    'constraints' => [new Assert\NotBlank() , new Assert\Email(['message' => 'Email incorrect'])]
                ])
                
                // On utilise RepeatedType afin de confirmer que les deux mots de passe soient identiques
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe ne correspondent pas',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,

                'constraints' => [new Assert\NotBlank(), new Assert\Length([

                    // On met une condition de taille de mot de passe minimale et maximale
                    'min' => 6, 'minMessage' => 'Votre mot de passe est trop court',
                    'max' => 12, 'maxMessage' => 'Votre mot de passe est trop long',]),
                    new Assert\Regex(

                    // On rajoute également une Regex pour faire en sorte que le mot de passe contienne au moins un chiffre
                        ['pattern' => '/\d/',
                        'message' => 'Votre mot de passe doit contenir au moins un chiffre'
                        ]
                    )],
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


