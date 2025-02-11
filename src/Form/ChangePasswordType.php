<?php
// src/Form/ChangePasswordType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current password', // Translated label
                'mapped' => false, // The field does not correspond to any property of the User entity
                'required' => true,
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New password', // Translated label
                'required' => true,
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm password', // Translated label
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Change password', // Translated label
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // No specific entity is linked to this form
        $resolver->setDefaults([
            'data_class' => null, // No class linked to this form
        ]);
    }
}
