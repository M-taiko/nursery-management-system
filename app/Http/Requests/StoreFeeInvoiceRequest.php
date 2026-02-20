<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Admin');
    }

    public function rules(): array
    {
        return [
            'child_id'      => 'required|exists:children,id',
            'fee_plan_id'   => 'nullable|exists:fee_plans,id',
            'amount'        => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'due_date'      => 'required|date',
            'notes'         => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'child_id.required'  => 'يجب تحديد الطفل',
            'child_id.exists'    => 'الطفل المحدد غير موجود',
            'amount.required'    => 'يجب إدخال المبلغ',
            'amount.min'         => 'يجب أن يكون المبلغ صفر أو أكثر',
            'due_date.required'  => 'يجب تحديد تاريخ الاستحقاق',
            'due_date.date'      => 'تاريخ الاستحقاق غير صحيح',
        ];
    }
}
