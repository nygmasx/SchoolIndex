<?php

// src/DataFixtures/SkillFixtures.php

namespace App\DataFixtures;

use App\Entity\File;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FileFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'file_';

    public const FILES = [
        [
            'name' => 'file1',
            'originalName' => 'original_file1.pdf',
            'extension' => 'pdf',
            'size' => 1024,
        ],
        [
            'name' => 'file2',
            'originalName' => 'original_file2.docx',
            'extension' => 'docx',
            'size' => 2048,
        ],
        [
            'name' => 'file3',
            'originalName' => 'original_file3.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file4',
            'originalName' => 'original_file4.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file5',
            'originalName' => 'original_file5.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file6',
            'originalName' => 'original_file6.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file7',
            'originalName' => 'original_file7.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file8',
            'originalName' => 'original_file8.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file9',
            'originalName' => 'original_file9.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        [
            'name' => 'file10',
            'originalName' => 'original_file10.pdf',
            'extension' => 'pdf',
            'size' => 3072,
        ],
        // Add more files here using the same structure
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::FILES as $i => $fileData) {
            $file = (new File())
                ->setName($fileData['name'])
                ->setOriginalName($fileData['originalName'])
                ->setExtension($fileData['extension'])
                ->setSize($fileData['size']);

            $manager->persist($file);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $file);
        }

        $manager->flush();
    }
}
