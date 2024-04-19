<?php

namespace App\DataFixtures;

use App\Entity\Thematic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

final class ThematicFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'thematic_';
    public const THEMATIC =
        [
            [
                'name' => 'Algèbres de Boole',
                'course' => 0,
            ],
            [
                'name' => 'L\'énergie ',
                'course' => 1,
            ],
            [
                'name' => 'Solution Acide/Basique',
                'course' => 2,
            ],
            [
                'name' => 'Conjugaison',
                'course' => 3,
            ],
            [
                'name' => 'La Historia de Madrid',
                'course' => 4,
            ],
            [
                'name' => 'La storia di Roma',
                'course' => 5,
            ],
            [
                'name' => 'MMA',
                'course' => 6,
            ],
            [
                'name' => 'Dystopic Writings',
                'course' => 7,
            ],
            [
                'name' => 'Les Contrats de Travail',
                'course' => 8,
            ],

        ];

    public function load(ObjectManager $manager): void
    {

        foreach (self::THEMATIC as $i => $thematic) {
            $severityLevel = (new Thematic())
                ->setName($thematic['name'])
                ->setCourse($this->getReference(CourseFixtures::REFERENCE_IDENTIFIER.$thematic['course']))
            ;

            $manager->persist($severityLevel);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $severityLevel);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CourseFixtures::class
        ];
    }
}
