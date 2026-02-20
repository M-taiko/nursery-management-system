<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BehaviorRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'child_id',
        'teacher_id',
        'record_date',
        'type',
        'category',
        'description',
        'action_taken',
        'parent_notified',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'parent_notified' => 'boolean',
        ];
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'إيجابي',
            'negative' => 'سلبي',
            'neutral' => 'محايد',
            default => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'positive' => 'green',
            'negative' => 'red',
            'neutral' => 'gray',
            default => 'gray',
        };
    }
}
