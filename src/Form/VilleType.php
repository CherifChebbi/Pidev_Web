<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_ville')
            ->add('img_ville', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
            ])
            ->add('desc_ville')
            ->add('pays',EntityType::class,[
                'class'=>Pays::class,
                'choice_label'=>'nom_pays',
                'multiple'=>false,//choix uniq ou mult
                'expanded'=>false,//liste- false
            ])
            ->add('latitude')
            ->add('longitude')  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
