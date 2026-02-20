<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id;

        return [
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:subjects,slug,' . $subjectId,
            'description' => 'nullable|string|max:1000',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'boolean',
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
            'name.required' => 'اسم المادة مطلوب',
        ];
    }
}
