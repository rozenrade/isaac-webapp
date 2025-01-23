<?php

namespace App\Form;

use App\Entity\Build;
use App\Entity\Item;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name', // Affiche le nom de l'item
                'multiple' => true, // Permet de sélectionner plusieurs items
                'expanded' => false,  // Affiche sous forme de liste déroulante
                'by_reference' => false, // Important pour les relations
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Build::class,
        ]);
    }
}
