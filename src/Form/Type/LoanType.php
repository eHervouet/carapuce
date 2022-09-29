<?php

namespace App\Form\Type;

use App\Entity\Loan;
use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('returnDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('destinationAddress')
            ->add('destination_city')
            ->add('destination_cp')
            ->add('affectedVehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => function($vehicle) {
                    return $vehicle->getBrand()." - ".$vehicle->getModel().' - '.$vehicle->getNbPlaces().' places';
                }])
            ->add('comment', TextareaType::class, array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
        ]);
    }
}
