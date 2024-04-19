<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Thematic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

final class CourseFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'course_';
    public const COURSE = [
        'Mathématiques',
        'Physique',
        'Chimie',
        'Français',
        'Espagnol',
        'Italien',
        'EPS',
        'Anglais',
        'CEJM',
    ];

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < \count(self::COURSE); ++$i) {
            $course = (new Course())
                ->setName(self::COURSE[$i]);

            $manager->persist($course);
            $this->addReference(self::REFERENCE_IDENTIFIER . $i, $course);
        }
        $manager->flush();
    }
}
