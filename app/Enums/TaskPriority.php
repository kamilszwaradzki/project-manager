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
            self::LOW    => 'bg-green-100 text-green-800',
            self::MEDIUM => 'bg-blue-100 text-blue-800',
            self::HIGH   => 'bg-orange-100 text-orange-800',
            self::URGENT => 'bg-red-100 text-red-800',
        };
    }

    // Scope do sortowania po priorytecie
    public function scopeOrderByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low') DESC");
    }
}
