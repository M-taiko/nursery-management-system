@extends('layouts.app')

@section('page-title', 'فاتورة #' . $invoice->invoice_number)

@push('styles')
<style>
/* ====================================================
   PRINT CSS - يُخفي كل شيء ويطبع الفاتورة فقط
   ==================================================== */
@media print {

    /* --- إجبار الألوان على الأبيض بغض النظر عن Dark Mode --- */
    *, *::before, *::after {
        background: white !important;
        color: #000 !important;
        box-shadow: none !important;
        text-shadow: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        border-color: #ccc !important;
    }

    /* --- إخفاء عناصر الـ UI --- */
    .sidebar, .top-navbar, .footer,
    .app-background, .sidebar-overlay,
    .no-print { display: none !important; }

    /* --- إزالة الـ layout --- */
    body, html { margin: 0; padding: 0; }
    .app-wrapper, .main-content, .container-fluid {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* --- إظهار الفاتورة المطبوعة --- */
    .invoice-print { display: block !important; }
    .invoice-screen { display: none !important; }

    /* --- تنسيق رأس الفاتورة --- */
    .inv-header {
        display: flex !important;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 3px solid #4e73df;
    }
    .inv-logo { display: flex; align-items: center; gap: 10px; }
    .inv-logo img { width: 60px; height: 60px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .inv-logo-text h1 { font-size: 20px; font-weight: 800; color: #4e73df !important; margin: 0; }
    .inv-logo-text p  { font-size: 11px; color: #666 !important; margin: 0; }
    .inv-title   { text-align: left; }
    .inv-title h2{ font-size: 22px; font-weight: 800; color: #4e73df !important; margin: 0; }
    .inv-title .inv-num { font-size: 12px; color: #555 !important; }
    .inv-badge {
        display: inline-block; padding: 3px 12px;
        border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 5px;
    }
    .inv-badge.paid    { border: 1px solid #10b981 !important; color: #10b981 !important; }
    .inv-badge.overdue { border: 1px solid #ef4444 !important; color: #ef4444 !important; }
    .inv-badge.partial { border: 1px solid #3b82f6 !important; color: #3b82f6 !important; }
    .inv-badge.pending { border: 1px solid #f59e0b !important; color: #f59e0b !important; }

    /* --- بيانات العميل --- */
    .inv-meta {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        background: #f8f9fa !important;
        padding: 12px; border-radius: 8px;
        margin-bottom: 18px;
        border: 1px solid #e5e7eb !important;
    }
    .inv-meta label { font-size: 9px; font-weight: 700; color: #888 !important; text-transform: uppercase; display: block; margin-bottom: 2px; }
    .inv-meta p     { font-size: 13px; font-weight: 600; color: #111 !important; margin: 0; }

    /* --- جدول المبالغ --- */
    .inv-amounts { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .inv-amounts th {
        background: #4e73df !important; color: white !important;
        padding: 9px 13px; font-size: 12px;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    .inv-amounts td { padding: 8px 13px; font-size: 12px; border-bottom: 1px solid #e5e7eb !important; }
    .inv-amounts tr:nth-child(even) td { background: #f9fafb !important; }
    .inv-amounts .tr-total td { font-weight: 700; font-size: 14px; background: #eff6ff !important; border-top: 2px solid #4e73df !important; }
    .inv-amounts .c-green { color: #059669 !important; }
    .inv-amounts .c-red   { color: #dc2626 !important; }
    .inv-amounts .c-blue  { color: #4e73df !important; }

    /* --- جدول الدفعات --- */
    .inv-payments { width: 100%; border-collapse: collapse; font-size: 11px; }
    .inv-payments th { background: #f1f5f9 !important; color: #333 !important; padding: 7px 11px; border: 1px solid #ddd !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .inv-payments td { padding: 7px 11px; border: 1px solid #eee !important; }
    .inv-payments h4 { font-size: 13px; margin: 0 0 8px; }

    /* --- تذييل الفاتورة --- */
    .inv-footer {
        text-align: center; margin-top: 28px;
        padding-top: 12px; border-top: 1px dashed #ccc !important;
        font-size: 10px; color: #888 !important;
    }

    @page { margin: 14mm; size: A4; }
}

/* على الشاشة: أخفِ محتوى الطباعة */
@media screen {
    .invoice-print { display: none; }
}
</style>
@endpush

@section('content')

{{-- ============================================================
     قسم الطباعة فقط (مخفي على الشاشة)
     ============================================================ --}}
<div class="invoice-print">
    <div class="inv-header">
        <div class="inv-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="شعار الحضانة">
            <div class="inv-logo-text">
                <h1>الحضانة</h1>
                <p>نظام الإدارة المتكامل</p>
            </div>
        </div>
        <div class="inv-title">
            <h2>فاتورة رسوم</h2>
            <div class="inv-num">رقم: {{ $invoice->invoice_number }}</div>
            <div class="inv-num">تاريخ: {{ now()->format('Y/m/d') }}</div>
            @if($invoice->status === 'paid')
                <span class="inv-badge paid">مدفوع</span>
            @elseif($invoice->isOverdue())
                <span class="inv-badge overdue">متأخر</span>
            @elseif($invoice->status === 'partial')
                <span class="inv-badge partial">مدفوع جزئياً</span>
            @else
                <span class="inv-badge pending">معلق</span>
            @endif
        </div>
    </div>

    <div class="inv-meta">
        <div><label>اسم الطفل</label><p>{{ $invoice->child->name }}</p></div>
        <div><label>ولي الأمر</label><p>{{ $invoice->child->parent->name }}</p></div>
        <div><label>خطة الرسوم</label><p>{{ $invoice->feePlan->name ?? '-' }}</p></div>
        <div><label>تاريخ الاستحقاق</label><p>{{ $invoice->due_date->format('Y/m/d') }}</p></div>
    </div>

    <table class="inv-amounts">
        <thead><tr><th>البيان</th><th style="text-align:left">المبلغ</th></tr></thead>
        <tbody>
            <tr><td>المبلغ الأساسي</td><td style="text-align:left">{{ number_format($invoice->amount, 2) }} ج.م</td></tr>
            <tr><td>الخصم</td><td class="c-green" style="text-align:left">- {{ number_format($invoice->discount, 2) }} ج.م</td></tr>
            <tr class="tr-total"><td>الإجمالي</td><td class="c-blue" style="text-align:left">{{ number_format($invoice->total, 2) }} ج.م</td></tr>
            <tr><td>المبلغ المدفوع</td><td class="c-green" style="text-align:left">{{ number_format($invoice->paid_amount, 2) }} ج.م</td></tr>
            <tr class="tr-total"><td>المتبقي</td><td class="c-red" style="text-align:left">{{ number_format($invoice->remaining_amount, 2) }} ج.م</td></tr>
        </tbody>
    </table>

    @if($invoice->payments->count() > 0)
    <div class="inv-payments">
        <h4>سجل الدفعات</h4>
        <table width="100%">
            <thead>
                <tr><th>التاريخ</th><th>المبلغ</th><th>طريقة الدفع</th><th>المستلم</th><th>ملاحظات</th></tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $p)
                @php $m = ['cash'=>'نقدي','bank_transfer'=>'تحويل بنكي','card'=>'بطاقة','credit_card'=>'بطاقة','check'=>'شيك']; @endphp
                <tr>
                    <td>{{ $p->payment_date->format('Y/m/d') }}</td>
                    <td>{{ number_format($p->amount, 2) }} ج.م</td>
                    <td>{{ $m[$p->payment_method] ?? $p->payment_method }}</td>
                    <td>{{ $p->receiver->name ?? '-' }}</td>
                    <td>{{ $p->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="inv-footer">
        <p>تم إصدار هذه الفاتورة بواسطة نظام إدارة الحضانة &mdash; {{ now()->format('Y/m/d H:i') }}</p>
        <p>للاستفسار يرجى التواصل مع إدارة الحضانة</p>
    </div>
</div>

{{-- ============================================================
     قسم الشاشة (يُخفى عند الطباعة)
     ============================================================ --}}
<div class="invoice-screen">
<div class="row g-4">

    {{-- العمود الرئيسي --}}
    <div class="col-lg-8">

        {{-- بطاقة تفاصيل الفاتورة --}}
        <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                    فاتورة رقم: <span class="text-primary">{{ $invoice->invoice_number }}</span>
                </h5>
                @if($invoice->status === 'paid')
                    <span class="badge bg-success px-3 py-2">مدفوع</span>
                @elseif($invoice->isOverdue())
                    <span class="badge bg-danger px-3 py-2">متأخر</span>
                @elseif($invoice->status === 'partial')
                    <span class="badge bg-info px-3 py-2">مدفوع جزئياً</span>
                @else
                    <span class="badge bg-warning text-dark px-3 py-2">معلق</span>
                @endif
            </div>

            <div class="card-body">
                {{-- معلومات أساسية --}}
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">الطفل</p>
                        <p class="fw-bold mb-0">
                            <i class="bi bi-person-fill text-primary me-1"></i>{{ $invoice->child->name }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">ولي الأمر</p>
                        <p class="fw-bold mb-0">
                            <i class="bi bi-people-fill text-secondary me-1"></i>{{ $invoice->child->parent->name }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">خطة الرسوم</p>
                        <p class="fw-bold mb-0">{{ $invoice->feePlan->name ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">تاريخ الاستحقاق</p>
                        <p class="fw-bold mb-0 {{ $invoice->isOverdue() ? 'text-danger' : '' }}">
                            <i class="bi bi-calendar-event me-1"></i>{{ $invoice->due_date->format('Y/m/d') }}
                        </p>
                    </div>
                </div>

                <hr style="border-color: var(--glass-border)">

                {{-- بطاقات المبالغ --}}
                <div class="row g-3">
                    <div class="col-4 text-center">
                        <div class="p-3 rounded-3" style="background: var(--glass-backdrop)">
                            <p class="text-muted small mb-1">المبلغ الأساسي</p>
                            <p class="fw-bold fs-5 mb-0">{{ number_format($invoice->amount, 2) }}</p>
                            <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="p-3 rounded-3" style="background: var(--glass-backdrop)">
                            <p class="text-muted small mb-1">الخصم</p>
                            <p class="fw-bold fs-5 text-success mb-0">- {{ number_format($invoice->discount, 2) }}</p>
                            <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="p-3 rounded-3" style="background: rgba(78,115,223,0.1)">
                            <p class="text-muted small mb-1">الإجمالي</p>
                            <p class="fw-bold fs-5 text-primary mb-0">{{ number_format($invoice->total, 2) }}</p>
                            <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="p-3 rounded-3" style="background: rgba(16,185,129,0.1)">
                            <p class="text-muted small mb-1">المدفوع</p>
                            <p class="fw-bold fs-4 text-success mb-0">{{ number_format($invoice->paid_amount, 2) }}</p>
                            <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="p-3 rounded-3" style="background: rgba(239,68,68,0.1)">
                            <p class="text-muted small mb-1">المتبقي</p>
                            <p class="fw-bold fs-4 text-danger mb-0">{{ number_format($invoice->remaining_amount, 2) }}</p>
                            <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                </div>

                @if($invoice->notes)
                <hr style="border-color: var(--glass-border)">
                <p class="text-muted small mb-1">ملاحظات</p>
                <p class="mb-0">{{ $invoice->notes }}</p>
                @endif
            </div>
        </div>

        {{-- سجل الدفعات --}}
        <div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history text-primary me-2"></i>سجل الدفعات
                </h5>
                @if($invoice->payments->count() > 0)
                    <span class="badge bg-primary rounded-pill">{{ $invoice->payments->count() }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-glass mb-0">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>المبلغ</th>
                                <th>الطريقة</th>
                                <th>المستلم</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->payments as $payment)
                            @php
                                $methods = [
                                    'cash'          => ['نقدي',         'bg-success'],
                                    'card'          => ['بطاقة',        'bg-primary'],
                                    'credit_card'   => ['بطاقة ائتمان', 'bg-primary'],
                                    'bank_transfer' => ['تحويل بنكي',   'bg-info'],
                                    'check'         => ['شيك',          'bg-secondary'],
                                ];
                                [$label, $cls] = $methods[$payment->payment_method] ?? [$payment->payment_method, 'bg-secondary'];
                            @endphp
                            <tr>
                                <td>{{ $payment->payment_date->format('Y/m/d') }}</td>
                                <td><span class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ج.م</span></td>
                                <td><span class="badge {{ $cls }}">{{ $label }}</span></td>
                                <td>{{ $payment->receiver->name ?? '-' }}</td>
                                <td class="text-muted small">{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state py-4">
                                        <i class="bi bi-cash-stack"></i>
                                        <p>لا توجد دفعات مسجلة بعد</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- العمود الجانبي --}}
    <div class="col-lg-4 no-print">

        {{-- إجراءات سريعة --}}
        <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-left">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge text-warning me-2"></i>إجراءات
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-2"></i>طباعة الفاتورة
                </button>
                <a href="{{ route('admin.fees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right me-2"></i>رجوع للقائمة
                </a>
            </div>
        </div>

        {{-- نموذج الدفع --}}
        @if($invoice->status !== 'paid' && $invoice->remaining_amount > 0)
        <div class="glass-card glass-card-no-hover" data-aos="fade-left" data-aos-delay="100">
            <div class="card-header" style="background: linear-gradient(135deg,#10b981,#059669); border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                <h5 class="text-white mb-0">
                    <i class="bi bi-cash-coin me-2"></i>تسجيل دفعة جديدة
                </h5>
            </div>
            <form action="{{ route('admin.fees.payment') }}" method="POST">
                @csrf
                <input type="hidden" name="fee_invoice_id" value="{{ $invoice->id }}">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">المبلغ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" step="0.01"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount', $invoice->remaining_amount) }}"
                                   max="{{ $invoice->remaining_amount }}" required>
                            <span class="input-group-text">ج.م</span>
                        </div>
                        <div class="form-text">المتبقي: <strong>{{ number_format($invoice->remaining_amount, 2) }} ج.م</strong></div>
                        @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">طريقة الدفع <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="">اختر...</option>
                            <option value="cash"          {{ old('payment_method') === 'cash'          ? 'selected' : '' }}>نقدي</option>
                            <option value="card"          {{ old('payment_method') === 'card'          ? 'selected' : '' }}>بطاقة</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="check"         {{ old('payment_method') === 'check'         ? 'selected' : '' }}>شيك</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">تاريخ الدفع <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date"
                               class="form-control @error('payment_date') is-invalid @enderror"
                               value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">رقم المرجع</label>
                        <input type="text" name="reference_number" class="form-control"
                               value="{{ old('reference_number') }}"
                               placeholder="رقم المعاملة / الشيك">
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-medium">ملاحظات</label>
                        <textarea name="notes" rows="2" class="form-control"
                                  placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success w-100 fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>تسجيل الدفعة
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

</div>
</div>
@endsection
