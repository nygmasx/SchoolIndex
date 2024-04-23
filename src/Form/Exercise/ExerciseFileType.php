<?php

namespace App\Form\Exercise;

use App\DataTransferObject\ExerciseFileDto;
use App\DataTransferObject\ExerciseSourceDto;
use App\Entity\Exercise;
use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseFileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exerciseFile', FileType::class, [
                "label" => "Origine :",
                'class' => File::class,
                'required' => true
            ])
            ->add('correctionFile', FileType::class, [
                "label" => "Origine :",
                'class' => File::class,
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExerciseFileDto::class,
        ]);
    }

}
