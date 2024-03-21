<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    // Ajoutez d'autres rôles selon vos besoins
                ],
                'expanded' => false, // false pour une liste déroulante, true pour des boutons radio
                'multiple' => true, // true si vous souhaitez permettre la sélection de plusieurs rôles, false pour un seul choix
                'label' => 'Rôles', // Optionnel: label du champ
            ])
            ->add('password', PasswordType::class)
            ->add('lastName')
            ->add('firstName');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
