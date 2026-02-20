@extends('layouts.app')

@section('page-title', 'إنشاء فاتورة جديدة')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.fees.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-file-earmark-plus me-2 text-primary"></i>إنشاء فاتورة جديدة</h1>
            <p class="page-subtitle">إنشاء فاتورة رسوم دراسية</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('admin.fees.store') }}" method="POST" id="invoiceForm">
            @csrf

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2 text-primary"></i>بيانات الفاتورة</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الطفل <span class="text-danger">*</span></label>
                            <select name="child_id" id="child_id"
                                    class="form-select @error('child_id') is-invalid @enderror" required>
                                <option value="">اختر الطفل...</option>
                                @foreach($children as $child)
                                <option value="{{ $child->id }}"
                                        data-stage="{{ $child->stage->name }}"
                                        {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                    {{ $child->name }} — {{ $child->stage->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('child_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">خطة الرسوم <span class="text-danger">*</span></label>
                            <select name="fee_plan_id" id="fee_plan_id"
                                    class="form-select @error('fee_plan_id') is-invalid @enderror" required>
                                <option value="">اختر خطة الرسوم...</option>
                                @foreach($feePlans as $plan)
                                <option value="{{ $plan->id }}"
                                        data-amount="{{ $plan->amount }}"
                                        data-stage="{{ $plan->stage->name }}"
                                        {{ old('fee_plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} — {{ number_format($plan->amount, 2) }} ج.م
                                </option>
                                @endforeach
                            </select>
                            @error('fee_plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card-header">
                    <h6><i class="bi bi-cash-coin me-2 text-primary"></i>تفاصيل المبالغ</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">المبلغ الأساسي <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount" id="amount" step="0.01"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" required placeholder="0.00">
                                <span class="input-group-text">ج.م</span>
                            </div>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الخصم</label>
                            <div class="input-group">
                                <input type="number" name="discount" id="discount" step="0.01"
                                       class="form-control @error('discount') is-invalid @enderror"
                                       value="{{ old('discount', 0) }}" placeholder="0.00">
                                <span class="input-group-text">ج.م</span>
                            </div>
                            @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">الإجمالي <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="total" id="total" step="0.01"
                                       class="form-control fw-600 text-primary @error('total') is-invalid @enderror"
                                       value="{{ old('total') }}" required readonly placeholder="0.00">
                                <span class="input-group-text">ج.م</span>
                            </div>
                            @error('total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header">
                    <h6><i class="bi bi-calendar3 me-2 text-primary"></i>الحالة والتواريخ</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الاستحقاق <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="due_date"
                                   class="form-control @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date') }}" required>
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الحالة <span class="text-danger">*</span></label>
                            <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="partial" {{ old('status') === 'partial' ? 'selected' : '' }}>مدفوع جزئياً</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between" data-aos="fade-up" data-aos-delay="150">
                <a href="{{ route('admin.fees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>إنشاء الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const feePlanSelect = document.getElementById('fee_plan_id');
    const amountInput   = document.getElementById('amount');
    const discountInput = document.getElementById('discount');
    const totalInput    = document.getElementById('total');

    feePlanSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (opt.value) {
            amountInput.value = parseFloat(opt.dataset.amount).toFixed(2);
            calculateTotal();
        } else {
            amountInput.value = '';
            totalInput.value  = '';
        }
    });

    amountInput.addEventListener('input', calculateTotal);
    discountInput.addEventListener('input', calculateTotal);

    function calculateTotal() {
        const amount   = parseFloat(amountInput.value)   || 0;
        const discount = parseFloat(discountInput.value) || 0;
        totalInput.value = Math.max(0, amount - discount).toFixed(2);
    }

    if (amountInput.value) calculateTotal();
});
</script>
@endpush
@endsection
