<?php

namespace App\Form;

use App\Entity\course;
use App\Entity\Exercise;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name')
        ->add('course', EntityType::class, [
            'class' => Course::class,
            'choice_label' => 'name',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer', 
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
