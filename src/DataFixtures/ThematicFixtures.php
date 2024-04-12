<?php

namespace App\DataFixtures;

use App\Entity\Thematic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThematicFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $thematicData = [
            [
                'name' => 'Calcul de Matrice',
            ],
            [
                'name' => 'Graphes et ordonnancement',
            ],
            [
                'name' => 'Arithmétique',
            ],
            [
                'name' => 'Algèbres de Boole',
            ],
            [
                'name' => 'Théorie des nombres',
            ],
            [
                'name' => 'Algorithmes de tri',
            ],
            [
                'name' => 'Probabilités et statistiques',
            ],
            [
                'name' => 'Géométrie',
            ],
            [
                'name' => 'Théorie des jeux',
            ],
        ];
        

        foreach ($thematicData as $thematicDatum) {
            $thematic = new Thematic();
            $thematic->setName($thematicDatum['name']);
            $manager->persist($thematic);

            // Ajout des références avec vérification de l'existence préalable
            $referenceName = 'thematic_' . $thematicDatum['name'];
            if ($this->hasReference($referenceName)) {
                $this->setReference($referenceName, $thematic);
            } else {
                $this->addReference($referenceName, $thematic);
            }
        }

        $manager->flush();
    }
}