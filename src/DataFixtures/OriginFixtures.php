<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Origin;
use App\Entity\Thematic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

final class OriginFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'origin_';
    public const ORIGIN = [
        'Livre',
        'Manuel',
        'Site Web',
        'Vidéo',
        'Cours',

    ];

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < \count(self::ORIGIN); ++$i) {
            $origin = (new Origin())
                ->setName(self::ORIGIN[$i]);

            $manager->persist($origin);
            $this->addReference(self::REFERENCE_IDENTIFIER . $i, $origin);
        }
        $manager->flush();
    }
}
