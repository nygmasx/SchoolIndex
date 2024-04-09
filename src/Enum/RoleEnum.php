<?php 

// src/Enum/RoleEnum.php

namespace App\Enum;

enum RoleEnum
{
    public const ADMIN = 'ROLE_ADMIN';
    public const TEACHER = 'ROLE_TEACHER';
    public const STUDENT = 'ROLE_STUDENT';

    public static function displayName(RoleEnum $roleEnum)
    {
        return match($roleEnum){
            RoleEnum::ADMIN => 'Administrateur',
            RoleEnum::STUDENT => 'Etudiant',
            RoleEnum::TEACHER => 'Enseignant',
        };
        
    }
}