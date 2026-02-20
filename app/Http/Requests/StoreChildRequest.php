<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Admin');
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'name_ar'           => 'nullable|string|max:255',
            'birth_date'        => 'required|date|before:today',
            'gender'            => 'required|in:male,female',
            'national_id'       => 'nullable|string|max:20|unique:children,national_id',
            'medical_notes'     => 'nullable|string|max:1000',
            'allergies'         => 'nullable|string|max:500',
            'blood_type'        => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'photo_consent'     => 'boolean',
            'parent_id'         => 'required|exists:users,id',
            'stage_id'          => 'required|exists:stages,id',
            'classroom_id'      => 'required|exists:classrooms,id',
            'enrollment_date'   => 'required|date',
            'status'            => 'required|in:active,inactive,graduated',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'اسم الطفل مطلوب',
            'birth_date.required'    => 'تاريخ الميلاد مطلوب',
            'birth_date.before'      => 'تاريخ الميلاد يجب أن يكون قبل اليوم',
            'gender.required'        => 'الجنس مطلوب',
            'gender.in'              => 'الجنس يجب أن يكون: ذكر أو أنثى',
            'national_id.unique'     => 'رقم الهوية الوطنية مستخدم مسبقاً',
            'parent_id.required'     => 'يجب تحديد ولي الأمر',
            'parent_id.exists'       => 'ولي الأمر المحدد غير موجود',
            'stage_id.required'      => 'يجب تحديد المرحلة',
            'classroom_id.required'  => 'يجب تحديد الفصل',
            'enrollment_date.required' => 'تاريخ الالتحاق مطلوب',
            'status.required'        => 'حالة الطفل مطلوبة',
            'photo.image'            => 'الملف يجب أن يكون صورة',
            'photo.max'              => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ];
    }
}
