<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Enum\RoleEnum;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email') 
            ->add('role', ChoiceType::class, [     //EnumType
                'choices' => [
                    'Etudiant' => RoleEnum::STUDENT,
                    'Enseignant' => RoleEnum::TEACHER,
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Rôles',
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'required' => false, // Rend le champ optionnel
                'constraints' => array_filter([
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                    $options['require_password'] ? new NotBlank([
                        'message' => 'Please enter a password',
                    ]) : null,
                ]),
            ])
            ->add('lastName')
            ->add('firstName')
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer', // Libellé par défaut du bouton de soumission
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true, // Par défaut, on requiert le mot de passe
        ]);
    }
}
