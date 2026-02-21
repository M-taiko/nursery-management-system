<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_ar',
        'birth_date',
        'gender',
        'photo',
        'national_id',
        'medical_notes',
        'allergies',
        'blood_type',
        'emergency_contact',
        'emergency_phone',
        'photo_consent',
        'parent_id',
        'stage_id',
        'classroom_id',
        'enrollment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'enrollment_date' => 'date',
            'photo_consent' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(DailyEvaluation::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ChildPhoto::class);
    }

    public function behaviorRecords(): HasMany
    {
        return $this->hasMany(BehaviorRecord::class);
    }

    public function feeInvoices(): HasMany
    {
        return $this->hasMany(FeeInvoice::class);
    }

    public function getAgeAttribute(): string
    {
        if (!$this->birth_date) {
            return '-';
        }
        $age = $this->birth_date->age;
        $months = $this->birth_date->diffInMonths(now()) % 12;
        return "{$age} سنة و {$months} شهر";
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return Storage::disk('public')->url($this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f472b6&color=fff';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStage($query, $stageId)
    {
        return $query->where('stage_id', $stageId);
    }

    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
}
