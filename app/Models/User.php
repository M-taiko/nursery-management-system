<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Parent: children
    public function children(): HasMany
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

    // Teacher: evaluations
    public function evaluations(): HasMany
    {
        return $this->hasMany(DailyEvaluation::class, 'teacher_id');
    }

    // Teacher: uploaded photos
    public function uploadedPhotos(): HasMany
    {
        return $this->hasMany(ChildPhoto::class, 'uploaded_by');
    }

    // Teacher: behavior records
    public function behaviorRecords(): HasMany
    {
        return $this->hasMany(BehaviorRecord::class, 'teacher_id');
    }

    // Teacher: assigned subjects with classrooms
    public function teacherSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject', 'teacher_id', 'subject_id')
            ->withPivot('classroom_id')
            ->withTimestamps();
    }

    // Teacher: assigned classrooms
    public function teacherClassrooms()
    {
        return $this->belongsToMany(Classroom::class, 'teacher_subject', 'teacher_id', 'classroom_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }
}
