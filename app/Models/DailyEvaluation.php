<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyEvaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'evaluation_date',
        'child_id',
        'subject_id',
        'teacher_id',
        'understanding_level',
        'comprehension_percentage',
        'curriculum_progress',
        'homework',
        'class_performance',
        'behavior',
        'teacher_notes',
        'is_absent',
        'absence_reason',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_date' => 'date',
            'comprehension_percentage' => 'integer',
            'is_absent' => 'boolean',
        ];
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('evaluation_date', $date);
    }

    public function scopeForChild($query, $childId)
    {
        return $query->where('child_id', $childId);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function getUnderstandingLabelAttribute(): string
    {
        return match ($this->understanding_level) {
            'excellent' => 'ممتاز',
            'very_good' => 'جيد جداً',
            'good' => 'جيد',
            'average' => 'متوسط',
            'needs_improvement' => 'يحتاج تحسين',
            default => $this->understanding_level,
        };
    }

    public function getBehaviorLabelAttribute(): string
    {
        return match ($this->behavior) {
            'excellent' => 'ممتاز',
            'very_good' => 'جيد جداً',
            'good' => 'جيد',
            'average' => 'متوسط',
            'needs_improvement' => 'يحتاج تحسين',
            default => $this->behavior,
        };
    }

    public function getPerformanceLabelAttribute(): string
    {
        return match ($this->class_performance) {
            'excellent' => 'ممتاز',
            'very_good' => 'جيد جداً',
            'good' => 'جيد',
            'average' => 'متوسط',
            'needs_improvement' => 'يحتاج تحسين',
            default => $this->class_performance,
        };
    }
}
