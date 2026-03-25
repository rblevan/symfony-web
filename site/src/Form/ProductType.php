<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', null, [
                'label' => 'Nom du produit'
            ])
            ->add('prixUnitaire', null, [
                'label' => 'Prix Unitaire'
            ])
            ->add('quantiteStock', null, [
                'label' => 'Quantité en stock'
            ])
            ->add('image', null, [
                'label' => 'Nom de l\'image (ex: bloc_tnt.png)'
            ])
        ;
    }
}
