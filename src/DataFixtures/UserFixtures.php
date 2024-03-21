<?php

// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userData = [
            [
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'roles' => ['ROLE_ADMIN'], // Définir explicitement le rôle ici
                'lastName' => 'admin',
                'firstName' => 'admin',
            ],
            [
                'email' => 'jean@gmail.com',
                'password' => 'jeanvaljean',
                'roles' => ['ROLE_USER'], // Définir explicitement le rôle ici
                'lastName' => 'Valjean',
                'firstName' => 'Jean',
            ],

            [
                'email' => 'javert@gmail.com',
                'password' => 'inspectorjavert',
                'roles' => ['ROLE_USER'], // Définir explicitement le rôle ici
                'lastName' => 'Javert',
                'firstName' => 'Inspector',
            ],


            [
                'email' => 'cosette@gmail.com',
                'password' => 'cosettefantine',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Fantine',
                'firstName' => 'Cosette',
            ],
            [
                'email' => 'marius@gmail.com',
                'password' => 'pontmercy',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Pontmercy',
                'firstName' => 'Marius',
            ],
            [
                'email' => 'enjolras@gmail.com',
                'password' => 'revolution',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Enjolras',
                'firstName' => 'Enjolras',
            ],
            [
                'email' => 'thenardier@gmail.com',
                'password' => 'masterofthehouse',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Thénardier',
                'firstName' => 'Thénardier',
            ],

            [
                'email' => 'elodie@gmail.com',
                'password' => 'elodiepass',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Martin',
                'firstName' => 'Elodie',
            ],

            [
                'email' => 'thomas@gmail.com',
                'password' => 'thomaspass',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Dupont',
                'firstName' => 'Thomas',
            ],
            [
                'email' => 'sophie@gmail.com',
                'password' => 'sophiepass',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Durand',
                'firstName' => 'Sophie',
            ],
            [
                'email' => 'lucas@gmail.com',
                'password' => 'lucaspass',
                'roles' => ['ROLE_USER'],
                'lastName' => 'Petit',
                'firstName' => 'Lucas',
            ],
            // Ajoutez d'autres utilisateurs si nécessaire
        ];

        foreach ($userData as $userDatum) {
            $user = new User();
            $user->setEmail($userDatum['email']);
            
            // Hachez le mot de passe avant de le définir
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userDatum['password']
            );
            $user->setPassword($hashedPassword);

            $user->setLastName($userDatum['lastName']);
            $user->setFirstName($userDatum['firstName']);
            
            // Attribuer le rôle défini dans $userData
            $user->setRoles($userDatum['roles']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
