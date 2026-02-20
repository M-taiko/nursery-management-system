<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'stage_id',
        'capacity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'classroom_id', 'teacher_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return $this->capacity - $this->children()->where('status', 'active')->count();
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_slots <= 0;
    }
}
