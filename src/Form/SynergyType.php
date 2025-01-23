<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Synergy;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SynergyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name',  // Afficher le nom de l'item
                'multiple' => true,        // Permet la sélection multiple
                'expanded' => false,       // Ne pas afficher en cases à cocher normales
            ])
            ->add('utilisateurs', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Synergy::class,
        ]);
    }
}
