<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

class ClassroomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des classes fictives
        $classesData = [
            'Collège' => ['6ème', '5ème', '4ème', '3ème'],
            'Lycée' => ['Seconde', 'Première', 'Terminale'],
            'BTS' => ['BTS01', 'BTS02'],
            'Autre' => ['Licence'],
        ];

        foreach ($classesData as $group => $classes) {
            foreach ($classes as $className) {
                $classroom = new Classroom();
                $classroom->setName($className);

                // Persiste l'entité
                $manager->persist($classroom);

                // Ajoute une référence
                $referenceName = 'classroom_' . strtolower(str_replace(' ', '_', $className));
                $this->addReference($referenceName, $classroom);
            }
        }

        // Flush toutes les opérations en base de données
        $manager->flush();
    }
}
