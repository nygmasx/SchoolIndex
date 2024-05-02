<?php

namespace App\Form\Exercise;

use App\DataTransferObject\ExerciseGeneralDto;
use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\Skill;
use App\Entity\Thematic;
use App\Enum\DifficultyLevelEnum;
use App\Form\Type\ChoiceTagType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class ExerciseGeneralInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de l'exercice",
                'required' => true
            ])
            ->add('course', EntityType::class, [
                "label" => "Matière",
                'placeholder' => 'Choisissez une matière',
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
            ->add('difficulty', EnumType::class, [
                'label' => 'Difficulté',
                'class' => DifficultyLevelEnum::class,
                'choice_label' => static fn(DifficultyLevelEnum $target): string => $target->value
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée (en heure)'
            ])
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
