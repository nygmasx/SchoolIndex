<?php

namespace App\Form\Exercise;

use App\Entity\Exercise;
use App\Entity\Origin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseSourceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('origin', EntityType::class, [
                "label" => "Origine :",
                'class' => Origin::class,
                'required' => true
            ])
            ->add('originName', TextType::class, [
                "label" => "Nom du livre/lien du site :",
            ])
            ->add('originInformation', TextType::class, [
                "label" => "Informations complémentaires :",
            ])
            ->add('proposedbyType', ChoiceType::class, [
                "label" => "Ou proposé par un :",
                'choices' => [
                    "Éleve" => "Éleve",
                    "Professeur" => "Professeur"
                ]
            ])
            ->add('proposedByFirstName', TextType::class, [
                "label" => "Nom :",
            ])
            ->add('proposedByLastName', TextType::class, [
                "label" => "Prénom :",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }

}
