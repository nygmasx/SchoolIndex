<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\Origin;
use App\Entity\Skill;
use App\Entity\Thematic;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de l'exercice",
                'required' => true
            ])
            ->add('chapter', TextType::class, [
                'label' => 'Chapitre'
            ])
            ->add('keywords', TextType::class, [
                'label' => 'Mots Clés',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Difficulté * :',
                'choices' => [
                    'Niveau 1' => 1,
                    'Niveau 2' => 2,
                    'Niveau 3' => 3,
                    'Niveau 4' => 4,
                    'Niveau 5' => 5,
                    'Niveau 6' => 6,
                    'Niveau 7' => 7,
                    'Niveau 8' => 8,
                    'Niveau 9' => 9,
                    'Niveau 10' => 10,
                    'Niveau 11' => 11,
                    'Niveau 12' => 12,
                    'Niveau 13' => 13,
                    'Niveau 14' => 14,
                    'Niveau 15' => 15,
                    'Niveau 16' => 16,
                    'Niveau 17' => 17,
                    'Niveau 18' => 18,
                    'Niveau 19' => 19,
                    'Niveau 20' => 20,
                ],
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée (en heure)'
            ])
            ->add('originName', TextType::class, [
                "label" => "Nom du livre/lien du site :",
            ])
            ->add('originInformation', TextType::class, [
                "label" => "Informations complémentaires :",
            ])
            ->add('proposedbyType', ChoiceType::class, [
                "label" => "Ou proposé par un :",
            ])
            ->add('proposedByFirstName', TextType::class, [
                "label" => "Nom :",
            ])
            ->add('proposedByLastName', TextType::class, [
                "label" => "Prénom :",
            ])
            ->add('course', EntityType::class, [
                "label" => "Matière",
                'class' => Course::class,
                'choice_label' => 'name',
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'label' => 'Classe',
                'choice_label' => 'name',
            ])
            ->add('thematic', EntityType::class, [
                'class' => Thematic::class,
                'label' => 'Thématique :',
                'choice_label' => 'name',
                'disabled' => 'true',
                'placeholder' => 'Veuillez séléctionner une matière'
            ])
            ->add('origin', EntityType::class, [
                "label" => "Origine :",
                'class' => Origin::class,
                'required' => true
            ])
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('firstFile', VichFileType::class, [
                "label" => "Fiche exercice (PDF, word) * :",
                'required' => true
            ])
            ->add('secondFile', VichFileType::class, [
                "label" => "Fiche corrigé (PDF, word) * :",
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
