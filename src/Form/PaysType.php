<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_pays')
            ->add('img_pays', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
            ])
            ->add('desc_pays')
            ->add('langue')
            ->add('continent', ChoiceType::class, [
                'label' => 'Continent',
                'choices' => [
                    'Europe' => 'Europe',
                    'Afrique' => 'Afrique',
                    'Amérique ' => 'Amérique',
                    'Asie' => 'Asie',
                    'Antarctique' => 'Antarctique',
                ],
                'placeholder' => 'Sélectionner un continent', // Optionnel, pour ajouter un placeholder
                'required' => true, // Optionnel, pour rendre le champ obligatoire
            ])
            ->add('latitude')
            ->add('longitude')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pays::class,
        ]);
    }
}
