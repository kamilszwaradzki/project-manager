<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
    ];

    public $timestamps = true;

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assigned_to(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
