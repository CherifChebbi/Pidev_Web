<?php

namespace App\Form;

use App\Entity\Monument;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_monument')
            ->add('img_monument', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
            ])
            ->add('desc_monument')
            ->add('villes',EntityType::class,[
                'class'=>Ville::class,
                'choice_label'=>'nom_ville',
                'multiple'=>false,//choix uniq ou mult
                'expanded'=>false,//liste- false
            ])
            ->add('latitude')
            ->add('longitude')
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monument::class,
        ]);
    }
}
