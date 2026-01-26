<?php

namespace App\Enums;

enum QuestionType: string
{
    // Question type enum values
    case SINGLE = 'single';
    case MULTIPLE = 'multiple';
    case TRUE_FALSE = 'true_false';
    case SHORT_ANSWER = 'short_answer';

    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'MCQ (Single)',
            self::MULTIPLE => 'Multiple Correct',
            self::TRUE_FALSE => 'True / False',
            self::SHORT_ANSWER => 'Short Answer',
        };
    }
}
