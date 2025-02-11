<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserProfileType extends AbstractType
{
    // Add 'currentPassword' field for confirmation
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username' // Translated label
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email' // Translated label
            ])
            // Current password field (security confirmation)
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current password', // Translated label
                'mapped' => false, // This field is not mapped to the User entity, it is just for validation
                'attr' => ['class' => 'w-full px-4 py-2 border rounded-md'],
                'required' => true, // This field is mandatory
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update' // Translated label
            ]);
    }
}
