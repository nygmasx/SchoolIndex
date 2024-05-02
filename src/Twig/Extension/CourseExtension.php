<?php

namespace App\Twig\Extension;

use App\Entity\Course;
use App\Twig\Runtime\CourseExtensionRuntime;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class CourseExtension extends AbstractExtension
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getFilters(): array
    {

        return [
            new TwigFilter('filter_name', [CourseExtensionRuntime::class, 'action']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menuCourses', [$this, 'getCourses']),
        ];
    }

    public function getCourses(): array
    {
        return $this->entityManager->getRepository(Course::class)->findBy([], ['name' => 'ASC']);
    }
}
