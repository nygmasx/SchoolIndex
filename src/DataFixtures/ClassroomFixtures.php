<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

class ClassroomFixtures extends Fixture
{

    public const REFERENCE_IDENTIFIER = 'classroom_';

    public const CLASSROOM = [
        '6ème',
        '5ème',
        '4ème',
        '3ème',
        'Seconde',
        'Première',
        'Terminale',
        'BTS-01',
        'BTS-02',
        'Licence',
    ];


    public function load(ObjectManager $manager): void
    {
        foreach (self::CLASSROOM as $i => $classroomData) {
            $classroom = (new Classroom())
                ->setName(self::CLASSROOM[$i]);

            $manager->persist($classroom);
            $this->addReference(self::REFERENCE_IDENTIFIER . $i, $classroom);
        }
        $manager->flush();
    }
}
