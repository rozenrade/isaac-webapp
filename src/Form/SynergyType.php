<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Synergy;
use App\Entity\User;
use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SynergyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name') // Champ pour le nom de la synergie
            ->add('item', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name',  // Affiche le nom des items
                'multiple' => true,        // Permet de sélectionner plusieurs items
                'expanded' => true,        // Affiche les options sous forme de cases à cocher
                'query_builder' => function (ItemRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC')
                        ->where('i.filename IS NOT NULL');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Synergy::class,
        ]);
    }
}
