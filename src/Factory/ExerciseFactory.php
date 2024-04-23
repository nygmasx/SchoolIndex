<?php

namespace App\Factory;

use App\DataTransferObject\ExerciseFileDto;
use App\DataTransferObject\ExerciseGeneralDto;
use App\DataTransferObject\ExerciseSourceDto;
use App\Entity\Exercise;
use App\Entity\User;

final class ExerciseFactory
{
    public function createFormDtos(
        ExerciseGeneralDto $exerciseGeneralDto,
        ExerciseSourceDto  $exerciseSourceDto,
        ExerciseFileDto    $exerciseFileDto,
        User               $createdBy,
    ): Exercise
    {
        $exercise = new Exercise(
            name: $exerciseGeneralDto->getName(),
            course: $exerciseGeneralDto->getCourse(),
            classroom: $exerciseGeneralDto->getClassroom(),
            thematic: $exerciseGeneralDto->getThematic(),
            chapter: $exerciseGeneralDto->getChapter(),
            keywords: $exerciseGeneralDto->getKeywords(),
            difficulty: $exerciseGeneralDto->getDifficulty(),
            duration: $exerciseGeneralDto->getDuration(),
            origin: $exerciseSourceDto->getOrigin(),
            originName: $exerciseSourceDto->getOriginName(),
            originInformation: $exerciseSourceDto->getOriginInformation(),
            proposedbyType: $exerciseSourceDto->getProposedByType(),
            proposedByFirstName: $exerciseSourceDto->getProposedByFirstName(),
            proposedByLastName: $exerciseSourceDto->getProposedByLastName(),
            createdBy: $createdBy,
            exerciseFile: $exerciseFileDto->getExerciseFile(),
            correctionFile: $exerciseFileDto->getCorrectionFile(),
        );


        return $exercise;
    }
}
