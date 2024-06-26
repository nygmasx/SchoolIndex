<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use App\Enum\DifficultyLevelEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Random\RandomException;

class ExerciseFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_IDENTIFIER = 'exercise_';

    public const EXERCISES = [
        [
            'name' => 'Factorisation polynomiale',
            'course' => 'course_0',
            'thematic' => 'thematic_3',
            'chapter' => 'Chapitre 2',
            'keywords' => 'algèbre@maths@calcul',
            'difficulty' => 1,
            'duration' => 45.5,
            'original_name' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'file' => 'file_0',
            'correction_file' => 'file_5',
            'created_by' => 'user_1',
        ],
        [
            'name' => "Dérivation d'une fonction exponentielle",
            'course' => 'course_1',
            'chapter' => 'Chapitre 3',
            'thematic' => 'thematic_3',
            'keywords' => 'algèbre@maths@calcul',
            'difficulty' => 1,
            'duration' => 200,
            'original_name' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'file' => 'file_4',
            'correction_file' => 'file_6',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Coordonnées',
            'course' => 'course_1',
            'chapter' => 'Chapitre 5',
            'thematic' => 'thematic_5',
            'keywords' => 'algèbre@maths@calcul',
            'difficulty' => 1,
            'duration' => 150,
            'original_name' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'file' => 'file_3',
            'correction_file' => 'file_7',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Molière, le malade imaginaire',
            'course' => 'course_0',
            'chapter' => 'Chapitre 5',
            'thematic' => 'thematic_2',
            'keywords' => 'théatre@molière',
            'difficulty' => 1,
            'duration' => 150,
            'original_name' => 'Le Malade Imaginaire',
            'originInformation' => 'Livre de molière',
            'proposedByType' => 'Livre',
            'file' => 'file_2',
            'correction_file' => 'file_8',
            'created_by' => 'user_2',
        ],
        [
            'name' => 'Paris Ville Lumière',
            'course' => 'course_0',
            'chapter' => 'Chapitre 2',
            'thematic' => 'thematic_1',
            'keywords' => 'paris@littérature@arts',
            'difficulty' => 1,
            'duration' => 90,
            'original_name' => 'classique&cie BTS',
            'origin_information' => 'Johan Faerber',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Virginie',
            'proposedByLasName' => 'Hougron',
            'file' => 'file_1',
            'correction_file' => 'file_9',
            'created_by' => 'user_2',
        ],
    ];


    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        foreach (self::EXERCISES as $i => $exerciseInfo) {
            $exercise = (new Exercise())
                ->setName($exerciseInfo['name'])
                ->setCourse($this->getReference($exerciseInfo['course']))
                ->setClassroom($this->getReference(ClassroomFixtures::REFERENCE_IDENTIFIER . random_int(0, count(ClassroomFixtures::CLASSROOM) - 1)))
                ->setThematic($this->getReference($exerciseInfo['thematic']))
                ->setChapter($exerciseInfo['chapter'])
                ->setKeywords($exerciseInfo['keywords'])
                ->setDuration($exerciseInfo['duration'])
                ->setOrigin($this->getReference(OriginFixtures::REFERENCE_IDENTIFIER . random_int(0, count(OriginFixtures::ORIGIN) - 1)))
                ->setoriginName($exerciseInfo['original_name'])
                ->setDifficulty($exerciseInfo['difficulty'])
                ->setOriginInformation('Exercice tiré du livre "Mathématiques avancées".')
                ->setProposedByType('Enseignant')
                ->setProposedByFirstName('Laurent')
                ->setProposedByLastName('Guyard')
                ->setExerciseFile($exerciseInfo['file'])
                ->setOriginalFirstFileName($exerciseInfo['file'])
                ->setOriginalSecondFileName($exerciseInfo['file'])
                ->setFirstFileExtension('pdf')
                ->setSecondFileExtension('pdf')
                ->setFirstFileSize(5678)
                ->setSecondFileSize(5678)
                ->setCorrectionFile($exerciseInfo['correction_file'])
                ->setCreatedBy($this->getReference(UserFixtures::REFERENCE_IDENTIFIER. $i))
                ->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

            $manager->persist($exercise);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $exercise);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ThematicFixtures::class,
            UserFixtures::class,
            OriginFixtures::class,
            CourseFixtures::class,
            SkillFixtures::class,
            ClassroomFixtures::class,
        ];
    }
}
