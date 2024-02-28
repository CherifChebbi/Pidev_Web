<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('localisation')
            ->add('image', FileType::class, [
                'label' => 'Image du restaurant',
                'required' => false, // Allow the form to be submitted without uploading a new image
                'mapped' => false, // This field is not mapped to the entity property
                'help' => 'Téléchargez une image pour le restaurant (format: jpg, jpeg, png)',
            ])
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
