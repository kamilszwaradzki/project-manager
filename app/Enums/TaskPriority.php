<?php
namespace App\Enums;

enum TaskPriority: string
{
    case LOW    = 'low';
    case MEDIUM = 'medium';
    case HIGH   = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match($this) {
            self::LOW    => 'Niski',
            self::MEDIUM => 'Średni',
            self::HIGH   => 'Wysoki',
            self::URGENT => 'Pilny',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LOW    => 'gray',
            self::MEDIUM => 'blue',
            self::HIGH   => 'orange',
            self::URGENT => 'red',
        };
    }

    // Scope do sortowania po priorytecie
    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low') DESC");
    }
}
