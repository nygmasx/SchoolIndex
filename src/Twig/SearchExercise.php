<?php

namespace App\Twig;

use App\Repository\ExerciseRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('search_exercise')] // Définir un nom explicite pour le composant
class SearchExercise
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;

    public function __construct(private ExerciseRepository $exerciseRepo)
    {
    }

    public function getExercises(): array
    {
        return $this->exerciseRepo->findByClassName($this->query);
        return $this->exerciseRepo->findByCourseName($this->query);
        return $this->exerciseRepo->findByKeywords($this->query);
        return $this->exerciseRepo->findByThematicName($this->query);
    }
}
