<?php

namespace App\Form;

use App\Entity\ReservationEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Event;

class ReservationEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('nom', TextType::class, [
        'label' => 'Nom',
        'attr' => [
            'placeholder' => 'Entrez votre nom'
        ]
    ])
    ->add('email', EmailType::class, [
        'label' => 'Email',
        'attr' => [
            'placeholder' => 'Entrez votre email'
        ]
    ])
    ->add('num_tel', TelType::class, [
        'label' => 'Numéro de téléphone',
        'attr' => [
            'placeholder' => 'Entrez votre numéro de téléphone'
        ]
    ])
    ->add('date_reservation', DateTimeType::class, [
        'label' => 'Date de réservation',
        'widget' => 'single_text', // Affiche un champ de texte avec un calendrier
        'placeholder' => 'Select a date', // Placeholder du champ de texte
        'attr' => [
            'placeholder' => 'Sélectionnez la date de réservation'
        ]
        ])
    ->add('id_event', EntityType::class, [
       'class' => Event::class,
         'choice_label' => 'titre', // Ou tout autre champ que vous souhaitez afficher dans la liste déroulante
         'label' => 'Événement',
         'attr' => [
             'placeholder' => 'Sélectionnez l\'événement'
         ]
         ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationEvent::class,
        ]);
    }
}
