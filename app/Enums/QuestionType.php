<?php

namespace App\Enums;

enum QuestionType: string
{
    // Question type enum values
    case SINGLE = 'single';
    case MULTIPLE = 'multiple';
    case TRUE_FALSE = 'true_false';
}
