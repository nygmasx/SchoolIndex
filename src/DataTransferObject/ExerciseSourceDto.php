<?php

namespace App\DataTransferObject;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Origin;
use App\Entity\Skill;
use App\Entity\Thematic;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class ExerciseSourceDto
{
    private Origin $origin;
    private null|string $originName = null;
    private null|string $originInformation = null;
    private null|string $proposedByType = null;
    private null|string $proposedByFirstName = null;
    private null|string $proposedByLastName;
    private CarbonImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new CarbonImmutable();
    }

    public function getOrigin(): Origin
    {
        return $this->origin;
    }

    public function setOrigin(Origin $origin): void
    {
        $this->origin = $origin;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getOriginName(): ?string
    {
        return $this->originName;
    }

    public function setOriginName(?string $originName): void
    {
        $this->originName = $originName;
    }

    public function getOriginInformation(): ?string
    {
        return $this->originInformation;
    }

    public function setOriginInformation(?string $originInformation): void
    {
        $this->originInformation = $originInformation;
    }

    public function getProposedByType(): ?string
    {
        return $this->proposedByType;
    }

    public function setProposedByType(?string $proposedByType): void
    {
        $this->proposedByType = $proposedByType;
    }

    public function getProposedByFirstName(): ?string
    {
        return $this->proposedByFirstName;
    }

    public function setProposedByFirstName(?string $proposedByFirstName): void
    {
        $this->proposedByFirstName = $proposedByFirstName;
    }

    public function getProposedByLastName(): ?string
    {
        return $this->proposedByLastName;
    }

    public function setProposedByLastName(?string $proposedByLastName): void
    {
        $this->proposedByLastName = $proposedByLastName;
    }

}
