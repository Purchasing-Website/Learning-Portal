<?php

namespace App\Enums;

enum ProgressStatus: string
{
    // progress status enum values
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case DROPPED = 'dropped';
}
