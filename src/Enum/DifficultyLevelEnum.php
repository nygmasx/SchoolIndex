<?php

namespace App\Enum;

use App\Enum\Traits\UtilsTrait;

enum DifficultyLevelEnum: string
{
    use UtilsTrait;
    case CAP = 'Niveau 3';
    case BAC = 'Niveau 4';
    case BACPLUS2 = 'Niveau 5';
    case BACPLUS3 = 'Niveau 6';
    case BACPLUS5 = 'Niveau 7';
    case BACPLUS8 = 'Niveau 8';

}
