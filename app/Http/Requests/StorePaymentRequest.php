<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('Admin');
    }

    public function rules(): array
    {
        return [
            'fee_invoice_id'   => 'required|exists:fee_invoices,id',
            'amount'           => 'required|numeric|min:0.01',
            'payment_method'   => 'required|in:cash,bank_transfer,credit_card,check',
            'payment_date'     => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'fee_invoice_id.required' => 'يجب تحديد الفاتورة',
            'fee_invoice_id.exists'   => 'الفاتورة المحددة غير موجودة',
            'amount.required'         => 'يجب إدخال المبلغ',
            'amount.min'              => 'يجب أن يكون المبلغ أكبر من صفر',
            'payment_method.required' => 'يجب تحديد طريقة الدفع',
            'payment_method.in'       => 'طريقة الدفع غير صحيحة',
            'payment_date.required'   => 'يجب تحديد تاريخ الدفع',
            'payment_date.date'       => 'تاريخ الدفع غير صحيح',
        ];
    }
}
