<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'tags',
        'due_date',
        'assigned_to'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_date' => 'date:Y-m-d',
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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function tagsList(): array
    {
        return $this->tags
            ? array_filter(array_map('trim', explode(',', $this->tags)))
            : [];
    }

    public static function normalizeTags(?string $tags): ?string
    {
        if (! $tags) {
            return null;
        }

        $unique = array_unique(array_filter(array_map('trim', explode(',', $tags))));

        return $unique ? implode(',', $unique) : null;
    }
}
