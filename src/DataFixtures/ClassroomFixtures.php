<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

class ClassroomFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'classroom_';
    public const CLASSROOM = ['6ème', '5ème', ''];
    public function load(ObjectManager $manager): void
    {


        // Boucle à travers les données et crée les classes
        foreach ($classesData as $index => $classData) {
            $classroom = new Classroom();
            $classroom->setName($classData['name']);
            $referenceName = 'classroom_' . $index; // Nom de référence unique
            $this->addReference($referenceName, $classroom);

            // Persiste l'entité
            $manager->persist($classroom);
        }

        // Flush toutes les opérations en base de données
        $manager->flush();
    }
}
