<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
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
            'difficulty' => 3,
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
            'difficulty' => 4,
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
            'difficulty' => 2,
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
            'difficulty' => 2,
            'duration' => 150,
            'original_name' => 'Le Malade Imaginaire',
            'originInformation' => 'Livre de molière',
            'proposedByType' => 'Livre',
            'file' => 'file_2',
            'correction_file' => 'file_8',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Paris Ville Lumière',
            'course' => 'course_0',
            'chapter' => 'Chapitre 2',
            'thematic' => 'thematic_1',
            'keywords' => 'paris@littérature@arts',
            'difficulty' => 2,
            'duration' => 90,
            'original_name' => 'classique&cie BTS',
            'origin_information' => 'Johan Faerber',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Virginie',
            'proposedByLasName' => 'Hougron',
            'file' => 'file_1',
            'correction_file' => 'file_9',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Pythagorean Theorem',
            'course' => 'course_1',
            'chapter' => 'Chapter 4',
            'thematic' => 'thematic_4',
            'keywords' => 'geometry@maths@trigonometry',
            'difficulty' => 3,
            'duration' => 120,
            'original_name' => 'Geometry Basics',
            'originInformation' => 'Exercise from the book "Geometry Basics".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Emily',
            'proposedByLasName' => 'Johnson',
            'file' => 'file_10',
            'correction_file' => 'file_15',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Solving Quadratic Equations',
            'course' => 'course_2',
            'chapter' => 'Chapter 6',
            'thematic' => 'thematic_3',
            'keywords' => 'algebra@maths@equations',
            'difficulty' => 4,
            'duration' => 180,
            'original_name' => 'Advanced Mathematics',
            'originInformation' => 'Exercise from the book "Advanced Mathematics".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'John',
            'proposedByLasName' => 'Smith',
            'file' => 'file_11',
            'correction_file' => 'file_16',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Integration by Substitution',
            'course' => 'course_2',
            'chapter' => 'Chapter 7',
            'thematic' => 'thematic_5',
            'keywords' => 'calculus@maths@integration',
            'difficulty' => 4,
            'duration' => 210,
            'original_name' => 'Calculus Concepts',
            'originInformation' => 'Exercise from the book "Calculus Concepts".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Alice',
            'proposedByLasName' => 'Brown',
            'file' => 'file_12',
            'correction_file' => 'file_17',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Vectors and Scalars',
            'course' => 'course_1',
            'chapter' => 'Chapter 3',
            'thematic' => 'thematic_2',
            'keywords' => 'physics@maths@vectors',
            'difficulty' => 3,
            'duration' => 150,
            'original_name' => 'Physics Fundamentals',
            'originInformation' => 'Exercise from the book "Physics Fundamentals".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Michael',
            'proposedByLasName' => 'Davis',
            'file' => 'file_13',
            'correction_file' => 'file_18',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Linear Algebra Basics',
            'course' => 'course_2',
            'chapter' => 'Chapter 5',
            'thematic' => 'thematic_4',
            'keywords' => 'algebra@maths@linear',
            'difficulty' => 3,
            'duration' => 120,
            'original_name' => 'Linear Algebra Essentials',
            'originInformation' => 'Exercise from the book "Linear Algebra Essentials".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Emma',
            'proposedByLasName' => 'Wilson',
            'file' => 'file_14',
            'correction_file' => 'file_19',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Riemann Sum',
            'course' => 'course_2',
            'chapter' => 'Chapter 8',
            'thematic' => 'thematic_5',
            'keywords' => 'calculus@maths@integration',
            'difficulty' => 4,
            'duration' => 180,
            'original_name' => 'Calculus Fundamentals',
            'originInformation' => 'Exercise from the book "Calculus Fundamentals".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Sophia',
            'proposedByLasName' => 'Martinez',
            'file' => 'file_15',
            'correction_file' => 'file_20',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Probability Distributions',
            'course' => 'course_2',
            'chapter' => 'Chapter 9',
            'thematic' => 'thematic_6',
            'keywords' => 'probability@maths@distributions',
            'difficulty' => 4,
            'duration' => 210,
            'original_name' => 'Probability Theory',
            'originInformation' => 'Exercise from the book "Probability Theory".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Olivia',
            'proposedByLasName' => 'Garcia',
            'file' => 'file_16',
            'correction_file' => 'file_21',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Geometric Transformations',
            'course' => 'course_1',
            'chapter' => 'Chapter 5',
            'thematic' => 'thematic_4',
            'keywords' => 'geometry@maths@transformations',
            'difficulty' => 3,
            'duration' => 150,
            'original_name' => 'Geometry Essentials',
            'originInformation' => 'Exercise from the book "Geometry Essentials".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Daniel',
            'proposedByLasName' => 'Lopez',
            'file' => 'file_17',
            'correction_file' => 'file_22',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Limits and Continuity',
            'course' => 'course_2',
            'chapter' => 'Chapter 10',
            'thematic' => 'thematic_5',
            'keywords' => 'calculus@maths@limits',
            'difficulty' => 4,
            'duration' => 180,
            'original_name' => 'Advanced Calculus',
            'originInformation' => 'Exercise from the book "Advanced Calculus".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Ava',
            'proposedByLasName' => 'Taylor',
            'file' => 'file_18',
            'correction_file' => 'file_23',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Systems of Linear Equations',
            'course' => 'course_2',
            'chapter' => 'Chapter 11',
            'thematic' => 'thematic_4',
            'keywords' => 'algebra@maths@systems',
            'difficulty' => 3,
            'duration' => 120,
            'original_name' => 'Algebraic Equations',
            'originInformation' => 'Exercise from the book "Algebraic Equations".',
            'proposedByType' => 'Teacher',
            'proposedByFirstName' => 'Ethan',
            'proposedByLasName' => 'Thomas',
            'file' => 'file_19',
            'correction_file' => 'file_24',
            'created_by' => 'user_1',
        ]
                
    ];


    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::EXERCISES as $i => $exerciseInfo) {
            $exercise = (new Exercise())
                ->setName($exerciseInfo['name'])
                ->setCourse($this->getReference($exerciseInfo['course']))
                ->setClassroom($this->getReference(ClassroomFixtures::REFERENCE_IDENTIFIER . random_int(0, count(ClassroomFixtures::CLASSROOM) - 1)))
                ->setThematic($this->getReference($exerciseInfo['thematic']))
                ->setChapter($exerciseInfo['chapter'])
                ->setKeywords($exerciseInfo['keywords'])
                ->setDifficulty($exerciseInfo['difficulty'])
                ->setDuration($exerciseInfo['duration'])
                ->setOrigin($this->getReference(OriginFixtures::REFERENCE_IDENTIFIER . random_int(0, count(OriginFixtures::ORIGIN) - 1)))
                ->setoriginName($exerciseInfo['original_name'])
                ->setOriginInformation('Exercice tiré du livre "Mathématiques avancées".')
                ->setProposedByType('Enseignant')
                ->setProposedByFirstName('Laurent')
                ->setProposedByLastName('Guyard')
                ->setExerciseFile($this->getReference($exerciseInfo['file']))
                ->setCorrectionFile($this->getReference($exerciseInfo['correction_file']))
                ->setCreatedBy($this->getReference($exerciseInfo['created_by']))
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
            FileFixtures::class,
            CourseFixtures::class,
            SkillFixtures::class,
            ClassroomFixtures::class,
        ];
    }
}
