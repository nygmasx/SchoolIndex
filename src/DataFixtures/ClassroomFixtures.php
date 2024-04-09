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
            ['name' => '6ème'],
            ['name' => '5ème'],
            ['name' => '4ème'],
            ['name' => '3ème'],
            ['name' => 'Seconde'],
            ['name' => 'Première'],
            ['name' => 'Terminale'],
            ['name' => '1ère année'],
            ['name' => '2ème année'],
            ['name' => 'Licence'], 
        ];

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

    /*public function getDependencies(): array
    {
        return [
            
        ];
    }*/
}
