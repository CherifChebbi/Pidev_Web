<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Entrez le titre de l\'événement',
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'placeholder' => 'Entrez la description de l\'événement',
                'rows' => 4,
            ],
        ])
        ->add('date_debut', DateType::class, [
            'label' => 'Date de début',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datepicker',
                'placeholder' => 'Sélectionnez une date de début',
            ],
        ])
        ->add('date_fin', DateType::class, [
            'label' => 'Date de fin',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datepicker',
                'placeholder' => 'Sélectionnez une date de fin',
            ],
        ])
        ->add('lieu', TextType::class, [
            'label' => 'Lieu',
            'attr' => [
                'placeholder' => 'Entrez le lieu de l\'événement',
            ],
        ])
        ->add('prix', NumberType::class, [
            'label' => 'Prix',
            'attr' => [
                'placeholder' => 'Entrez le prix de l\'événement',
            ],
        ])
        ->add('image_event', FileType::class, [
            'label' => 'Image',
            'required' => false,
            'mapped' => false,
        ])
        
        ->add('Idcategory', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'nom',
            'label' => 'Catégorie',
            'placeholder' => 'Sélectionnez une catégorie',
            'required' => true,

        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
