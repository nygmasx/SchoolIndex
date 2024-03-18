<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\Origin;
use App\Entity\Skill;
use App\Entity\Thematic;
use App\Entity\User;
use App\Form\Type\ChoiceTagType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeneralExerciseInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('chapter', TextType::class, [
                'label' => 'Chapitre'
            ])
            ->add('keywords', ChoiceTagType::class, [
                'label' => 'Mots Clés'
            ])
            ->add('difficulty')
            ->add('duration')
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'id',
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'id',
            ])
            ->add('thematic', EntityType::class, [
                'class' => Thematic::class,
                'choice_label' => 'id',
            ])
            ->add('skill', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'id',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
