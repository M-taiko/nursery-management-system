<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChildPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Teacher');
    }

    public function rules(): array
    {
        return [
            'child_id'    => 'required|exists:children,id',
            'photos'      => 'required|array|min:1',
            'photos.*'    => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activity'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo_date'  => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required'    => 'يجب تحديد الطفل',
            'child_id.exists'      => 'الطفل المحدد غير موجود',
            'photos.required'      => 'يجب رفع صورة واحدة على الأقل',
            'photos.*.image'       => 'الملف يجب أن يكون صورة',
            'photos.*.mimes'       => 'صيغة الصورة يجب أن تكون: jpeg, png, jpg, webp',
            'photos.*.max'         => 'حجم الصورة يجب ألا يتجاوز 5 ميجابايت',
            'photo_date.required'  => 'يجب تحديد تاريخ الصورة',
        ];
    }
}
