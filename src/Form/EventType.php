<?php

// src/Form/EventType.php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'événement'
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
            ])
            ->add('locationLatitude', TextType::class, [
                'label' => 'Latitude',
            ])
            ->add('locationLongitude', TextType::class, [
                'label' => 'Longitude',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer l\'événement'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
