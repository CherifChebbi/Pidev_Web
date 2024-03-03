<?php

namespace App\Form;

use App\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('image', FileType::class, [
                'label' => 'Image du plat',
                'required' => false,
                'mapped' => false,
                'help' => 'Téléchargez une image pour le plat (format: jpg, jpeg, png)',
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix du plat',
                'currency' => 'EUR', // Assuming Euro currency, adjust as needed
            ])
            ->add('restaurant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
