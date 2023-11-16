<?php

declare(strict_types=1);

namespace App\Enum;

enum LevelsEnum: int {
    case ONE = 20;
    case TWO = 50;
    case THREE = 100;
    case FOUR = 200;
    case FIVE = 500;
    case SIX = 1000;
}
