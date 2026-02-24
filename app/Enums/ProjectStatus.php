<?php

namespace App\Enums;

enum ProjectStatus: string {
    /**
     * Not Started: The project has not started, and work on the project will start in future.
     * In progress: The project is currently being worked on by the project team.
     * Cancelled: The project has not finished, and work on the project will not continue.
     * Completed: Work on the project has finished, and all deliverables/tasks have been completed.
     * On Hold: The project has not finished, and work on the project has been temporarily suspended.
     */
    case NOT_STARTED = 'not-started';
    case IN_PROGRESS = 'in-progress';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case ON_HOLD = 'on-hold';
}
