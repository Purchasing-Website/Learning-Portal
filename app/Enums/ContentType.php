<?php

namespace App\Enums;

enum ContentType: string
{
    // Content types for lessons
    case Document = 'Document';
    case Video = 'Video';
    case Image = 'Image';
}
