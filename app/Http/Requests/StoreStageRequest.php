<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $stageId = $this->route('stage')?->id;

        return [
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:stages,slug,' . $stageId,
            'description' => 'nullable|string|max:1000',
            'age_from'    => 'required|integer|min:0|max:18',
            'age_to'      => 'required|integer|min:0|max:18|gte:age_from',
            'monthly_fee' => 'required|numeric|min:0',
            'is_active'   => 'boolean',
            'subjects'    => 'nullable|array',
            'subjects.*'  => 'exists:subjects,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && $this->name) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }

        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'اسم المرحلة مطلوب',
            'age_from.required'    => 'الحد الأدنى للسن مطلوب',
            'age_to.required'      => 'الحد الأقصى للسن مطلوب',
            'age_to.gte'           => 'الحد الأقصى للسن يجب أن يكون أكبر من أو يساوي الحد الأدنى',
            'monthly_fee.required' => 'الرسوم الشهرية مطلوبة',
            'monthly_fee.numeric'  => 'الرسوم الشهرية يجب أن تكون رقماً',
        ];
    }
}
