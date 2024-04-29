<?php

namespace App\DataTransferObject;

use App\Entity\File;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

class ExerciseFileDto
{
    private File $exerciseFile;
    private File $correctionFile;
    private CarbonImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new CarbonImmutable();
    }

    public function getExerciseFile(): File
    {
        return $this->exerciseFile;
    }

    public function setExerciseFile(File $exerciseFile): void
    {
        $this->exerciseFile = $exerciseFile;
    }

    public function getCorrectionFile(): File
    {
        return $this->correctionFile;
    }

    public function setCorrectionFile(File $correctionFile): void
    {
        $this->correctionFile = $correctionFile;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
