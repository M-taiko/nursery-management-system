<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBehaviorRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Teacher');
    }

    public function rules(): array
    {
        return [
            'child_id'      => 'required|exists:children,id',
            'record_date'   => 'required|date',
            'type'          => 'required|in:positive,negative,neutral',
            'category'      => 'nullable|string|max:100',
            'description'   => 'required|string|max:1000',
            'action_taken'  => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required'    => 'يجب تحديد الطفل',
            'child_id.exists'      => 'الطفل المحدد غير موجود',
            'record_date.required' => 'يجب تحديد تاريخ السجل',
            'record_date.date'     => 'تاريخ السجل غير صحيح',
            'type.required'        => 'يجب تحديد نوع السلوك',
            'type.in'              => 'نوع السلوك يجب أن يكون: إيجابي، سلبي، أو محايد',
            'description.required' => 'يجب كتابة وصف السلوك',
        ];
    }
}
