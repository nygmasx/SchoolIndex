<?php

namespace App\Twig;

use App\Entity\Course;
use App\Repository\SkillRepository;
use App\Repository\ThematicRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('exercise_form')]
class FormComponent
{
    use DefaultActionTrait;
    public ?Course $course = null;
    public array $skills = [];
    public array $thematics = [];

    public function __construct(
        private SkillRepository    $skillRepository,
        private ThematicRepository $thematicRepository
    )
    {
    }

    public function loadSkills(): void
    {
        $this->skills = $this->skillRepository->findBy(['course' => $this->course]);
    }

    public function loadThematics(): void
    {
        $this->thematics = $this->thematicRepository->findBy(['course' => $this->course]);
    }

    public function setCourse(Course $course): void
    {
        $this->course = $course;
        $this->loadSkills();
        $this->loadThematics();
    }
}
