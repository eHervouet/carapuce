<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Person;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères.',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('age')
            ->add('phoneNumber', TelType::class)
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'Utilisateur' => 'ROLE_USER',
                    'Gestionnaire de parc' => 'ROLE_GESTIONNAIRE'
                ], 
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            "constraints" => [
                new UniqueEntity([
                    'entityClass' => Person::class,
                    'fields' => 'email',
                    'message' => 'Cette adresse email est déjà utilisée.'
                ])]
        ]);
    }
}