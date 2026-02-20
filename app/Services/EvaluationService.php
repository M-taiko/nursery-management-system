<?php

namespace App\Services;

use App\Models\DailyEvaluation;

class EvaluationService
{
    public function getEvaluations(array $filters = [])
    {
        $query = DailyEvaluation::with(['child', 'subject', 'teacher']);

        if (!empty($filters['date'])) {
            $query->whereDate('evaluation_date', $filters['date']);
        }

        if (!empty($filters['child_id'])) {
            $query->where('child_id', $filters['child_id']);
        }

        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        if (!empty($filters['teacher_id'])) {
            $query->where('teacher_id', $filters['teacher_id']);
        }

        if (!empty($filters['classroom_id'])) {
            $query->whereHas('child', function($q) use ($filters) {
                $q->where('classroom_id', $filters['classroom_id']);
            });
        }

        return $query->latest('evaluation_date')->paginate(15);
    }

    public function createEvaluation(array $data, $teacherId): DailyEvaluation
    {
        $data['teacher_id'] = $teacherId;
        return DailyEvaluation::create($data);
    }

    public function updateEvaluation(DailyEvaluation $evaluation, array $data): DailyEvaluation
    {
        $evaluation->update($data);
        return $evaluation;
    }

    public function getChildEvaluationReport($childId, $from, $to)
    {
        return DailyEvaluation::where('child_id', $childId)
            ->whereBetween('evaluation_date', [$from, $to])
            ->with(['subject', 'teacher'])
            ->orderBy('evaluation_date', 'desc')
            ->get();
    }

    public function getClassroomDailyReport($classroomId, $date)
    {
        return DailyEvaluation::whereDate('evaluation_date', $date)
            ->whereHas('child', function($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            })
            ->with(['child', 'subject', 'teacher'])
            ->get()
            ->groupBy('child_id');
    }
}
