<?php

namespace App\DataTransferObject;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Skill;
use App\Entity\Thematic;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class ExerciseGeneralDto
{
    private ?string $name = null;
    private Course $course;
    private Classroom $classroom;
    private Thematic $thematic;
    private ?string $chapter = null;
    private ?string $keywords = null;
    private ?string $difficulty = null;
    private ?string $duration = null;
    private ArrayCollection $skills;
    private CarbonImmutable $createdAt;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): void
    {
        $this->course = $course;
    }

    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    public function setChapter(?string $chapter): void
    {
        $this->chapter = $chapter;
    }

    public function getClassroom(): Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(Classroom $classroom): void
    {
        $this->classroom = $classroom;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): void
    {
        $this->keywords = $keywords;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): void
    {
        $this->duration = $duration;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function getSkills(): ArrayCollection
    {
        return $this->skills;
    }

    public function setSkills(ArrayCollection $skills): void
    {
        $this->skills = $skills;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getThematic(): Thematic
    {
        return $this->thematic;
    }

    public function setThematic(Thematic $thematic): void
    {
        $this->thematic = $thematic;
    }


}
