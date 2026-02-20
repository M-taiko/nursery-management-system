<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Teacher');
    }

    public function rules(): array
    {
        $levels = 'in:excellent,very_good,good,average,needs_improvement';

        return [
            'child_id'                => 'required|exists:children,id',
            'subject_id'              => 'required|exists:subjects,id',
            'evaluation_date'         => 'required|date',
            'understanding_level'     => "required|$levels",
            'comprehension_percentage'=> 'nullable|integer|min:0|max:100',
            'curriculum_progress'     => 'nullable|string|max:500',
            'homework'                => "nullable|$levels",
            'class_performance'       => "nullable|$levels",
            'behavior'                => "nullable|$levels",
            'teacher_notes'           => 'nullable|string|max:1000',
            'is_absent'               => 'boolean',
            'absence_reason'          => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required'            => 'يجب تحديد الطفل',
            'child_id.exists'              => 'الطفل المحدد غير موجود',
            'subject_id.required'          => 'يجب تحديد المادة',
            'subject_id.exists'            => 'المادة المحددة غير موجودة',
            'evaluation_date.required'     => 'يجب تحديد تاريخ التقييم',
            'understanding_level.required' => 'يجب تحديد مستوى الفهم',
            'understanding_level.in'       => 'مستوى الفهم غير صحيح',
            'comprehension_percentage.min' => 'النسبة يجب أن تكون بين 0 و 100',
            'comprehension_percentage.max' => 'النسبة يجب أن تكون بين 0 و 100',
        ];
    }
}
