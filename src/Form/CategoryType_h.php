<?php

namespace App\Form;

use App\Entity\Category_h;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType_h extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('image', FileType::class, [
                'label' => 'Image du hebergement',
                'required' => false, // Allow the form to be submitted without uploading a new image
                'mapped' => false, // This field is not mapped to the entity property
                
            ])
            ->add('description')
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category_h::class,
        ]);
    }
}
