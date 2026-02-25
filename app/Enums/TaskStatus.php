<?php

namespace App\Enums;

enum TaskStatus: string {
    case TODO = 'todo';
    case IN_PROGRESS = 'in-progress';
    case REVIEW = 'review';
    case DONE = 'done';
}
