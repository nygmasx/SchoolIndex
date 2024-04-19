<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\RoleEnum;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public const REFERENCE_IDENTIFIER = 'user_';

    public const USER = [
        [
            'email' => 'admin@example.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::ADMIN],
            'lastName' => 'admin',
            'firstName' => 'admin',
        ],
        [
            'email' => 'jean@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Valjean',
            'firstName' => 'Jean',
        ],

        [
            'email' => 'javert@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Javert',
            'firstName' => 'Inspector',
        ],


        [
            'email' => 'cosette@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Fantine',
            'firstName' => 'Cosette',
        ],
        [
            'email' => 'marius@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Pontmercy',
            'firstName' => 'Marius',
        ],
        [
            'email' => 'enjolras@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Enjolras',
            'firstName' => 'Enjolras',
        ],
        [
            'email' => 'thenardier@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Thénardier',
            'firstName' => 'Thénardier',
        ],

        [
            'email' => 'elodie@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::STUDENT],
            'lastName' => 'Martin',
            'firstName' => 'Elodie',
        ],

        [
            'email' => 'thomas@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::TEACHER],
            'lastName' => 'Dupont',
            'firstName' => 'Thomas',
        ],
        [
            'email' => 'sophie@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::TEACHER],
            'lastName' => 'Durand',
            'firstName' => 'Sophie',
        ],
        [
            'email' => 'lucas@gmail.com',
            'password' => 'xxx',
            'roles' => [RoleEnum::TEACHER],
            'lastName' => 'Petit',
            'firstName' => 'Lucas',
        ],
        // Ajoutez d'autres utilisateurs si nécessaire
    ];

    public function load(ObjectManager $manager): void
    {


        foreach (self::USER as $i => $userDatum) {
            $user = new User();
            $user->setEmail($userDatum['email']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userDatum['password']
            );
            $user->setPassword($hashedPassword);

            $user->setLastName($userDatum['lastName']);
            $user->setFirstName($userDatum['firstName']);

            // Utilisation des constantes de RoleEnum pour définir les rôles
            $user->setRoles($userDatum['roles']);

            $manager->persist($user);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i , $user);
        }

        $manager->flush();
    }
}
