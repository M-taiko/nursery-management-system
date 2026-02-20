<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'age_from',
        'age_to',
        'monthly_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'monthly_fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'stage_subject')->withTimestamps();
    }

    public function feePlans(): HasMany
    {
        return $this->hasMany(FeePlan::class);
    }

    public function getAgeRangeAttribute(): string
    {
        return "{$this->age_from} - {$this->age_to} سنة";
    }
}
