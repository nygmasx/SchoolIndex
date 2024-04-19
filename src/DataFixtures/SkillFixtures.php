<?php

// src/DataFixtures/SkillFixtures.php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'skill_';

    public const SKILLS = [
        [
            'skill' => 'Lecture',
            'course' => 0,
        ],
        [
            'skill' => 'Écriture',
            'course' => 0,
        ],
        [
            'skill' => 'Grammaire',
            'course' => 0,
        ],
        [
            'skill' => 'Algèbre',
            'course' => 1,
        ],
        [
            'skill' => 'Géométrie',
            'course' => 1,
        ],
        [
            'skill' => 'Statistiques',
            'course' => 1,
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::SKILLS as $i => $skillInfo) {
            $skill = (new Skill())
                ->setName($skillInfo['skill'])
                ->setCourse($this->getReference(CourseFixtures::REFERENCE_IDENTIFIER.$skillInfo['course']));

            $manager->persist($skill);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $skill);
        }

        $manager->flush();
    }
}
