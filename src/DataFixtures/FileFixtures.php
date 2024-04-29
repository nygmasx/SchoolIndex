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
        [
            'name' => 'file11',
            'originalName' => 'original_file11.pdf',
            'extension' => 'pdf',
            'size' => 4096,
        ],
        [
            'name' => 'file12',
            'originalName' => 'original_file12.docx',
            'extension' => 'docx',
            'size' => 5120,
        ],
        [
            'name' => 'file13',
            'originalName' => 'original_file13.pdf',
            'extension' => 'pdf',
            'size' => 6144,
        ],
        [
            'name' => 'file14',
            'originalName' => 'original_file14.pdf',
            'extension' => 'pdf',
            'size' => 7168,
        ],
        [
            'name' => 'file15',
            'originalName' => 'original_file15.pdf',
            'extension' => 'pdf',
            'size' => 8192,
        ],
        [
            'name' => 'file16',
            'originalName' => 'original_file16.pdf',
            'extension' => 'pdf',
            'size' => 9216,
        ],
        [
            'name' => 'file17',
            'originalName' => 'original_file17.pdf',
            'extension' => 'pdf',
            'size' => 10240,
        ],
        [
            'name' => 'file18',
            'originalName' => 'original_file18.pdf',
            'extension' => 'pdf',
            'size' => 11264,
        ],
        [
            'name' => 'file19',
            'originalName' => 'original_file19.pdf',
            'extension' => 'pdf',
            'size' => 12288,
        ],
        [
            'name' => 'file20',
            'originalName' => 'original_file20.pdf',
            'extension' => 'pdf',
            'size' => 13312,
        ],
        [
            'name' => 'file21',
            'originalName' => 'original_file21.pdf',
            'extension' => 'pdf',
            'size' => 14336,
        ],
        [
            'name' => 'file22',
            'originalName' => 'original_file22.docx',
            'extension' => 'docx',
            'size' => 15360,
        ],
        [
            'name' => 'file23',
            'originalName' => 'original_file23.pdf',
            'extension' => 'pdf',
            'size' => 16384,
        ],
        [
            'name' => 'file24',
            'originalName' => 'original_file24.pdf',
            'extension' => 'pdf',
            'size' => 17408,
        ],
        [
            'name' => 'file25',
            'originalName' => 'original_file25.pdf',
            'extension' => 'pdf',
            'size' => 18432,
        ],
        [
            'name' => 'file26',
            'originalName' => 'original_file26.pdf',
            'extension' => 'pdf',
            'size' => 19456,
        ],
        [
            'name' => 'file27',
            'originalName' => 'original_file27.pdf',
            'extension' => 'pdf',
            'size' => 20480,
        ],
        [
            'name' => 'file28',
            'originalName' => 'original_file28.pdf',
            'extension' => 'pdf',
            'size' => 21504,
        ],
        [
            'name' => 'file29',
            'originalName' => 'original_file29.pdf',
            'extension' => 'pdf',
            'size' => 22528,
        ],
        [
            'name' => 'file30',
            'originalName' => 'original_file30.pdf',
            'extension' => 'pdf',
            'size' => 23552,
        ],        
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
            $this->addReference(self::REFERENCE_IDENTIFIER . $i, $file);
        }

        $manager->flush();
    }
}
