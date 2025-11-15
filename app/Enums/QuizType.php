<?php

namespace App\Enums;

enum QuizType: string   
{
    // Quiz type enum values
    case FINAL = 'FinalQuiz';
    case KNOWLEDGE_CHECK = 'KnowledgeCheck';
}
