<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

final class ClassroomFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'classroom_';
    public const CLASSROOM = ['6ème', '5ème', '4ème', '3ème', 'Seconde', 'Première', 'Terminale', 'BTS1', 'BTS2', 'Licence'];
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < \count(self::CLASSROOM); ++$i) {
            $supportCategory = (new Classroom())
                ->setName(self::CLASSROOM[$i]);

            $manager->persist($supportCategory);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $supportCategory);
        }
        $manager->flush();
    }
}
