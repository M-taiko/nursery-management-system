@extends('layouts.app')

@section('page-title', 'فاتورة ' . $invoice->invoice_number)

@push('styles')
<style>
@media print {
    *, *::before, *::after {
        background: white !important;
        color: #000 !important;
        box-shadow: none !important;
        text-shadow: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        border-color: #ccc !important;
    }
    .sidebar, .top-navbar, .footer, .app-background, .sidebar-overlay, .no-print { display: none !important; }
    body, html { margin: 0; padding: 0; }
    .app-wrapper, .main-content, .container-fluid {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .invoice-print { display: block !important; }
    .invoice-screen { display: none !important; }

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
    .inv-badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 5px; }
    .inv-badge.paid    { border: 1px solid #10b981 !important; color: #10b981 !important; }
    .inv-badge.overdue { border: 1px solid #ef4444 !important; color: #ef4444 !important; }
    .inv-badge.partial { border: 1px solid #3b82f6 !important; color: #3b82f6 !important; }
    .inv-badge.pending { border: 1px solid #f59e0b !important; color: #f59e0b !important; }

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

    .inv-payments { width: 100%; border-collapse: collapse; font-size: 11px; }
    .inv-payments th { background: #f1f5f9 !important; color: #333 !important; padding: 7px 11px; border: 1px solid #ddd !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .inv-payments td { padding: 7px 11px; border: 1px solid #eee !important; }
    .inv-payments h4 { font-size: 13px; margin: 0 0 8px; }

    .inv-footer {
        text-align: center; margin-top: 28px;
        padding-top: 12px; border-top: 1px dashed #ccc !important;
        font-size: 10px; color: #888 !important;
    }

    @page { margin: 14mm; size: A4; }
}
@media screen {
    .invoice-print { display: none; }
}
</style>
@endpush

@section('content')

{{-- قسم الطباعة (مخفي على الشاشة) --}}
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
        @if($invoice->feePlan)
        <div><label>خطة الرسوم</label><p>{{ $invoice->feePlan->name }}</p></div>
        @endif
        <div><label>تاريخ الاستحقاق</label><p>{{ $invoice->due_date->format('Y/m/d') }}</p></div>
    </div>

    <table class="inv-amounts">
        <thead><tr><th>البيان</th><th style="text-align:left">المبلغ</th></tr></thead>
        <tbody>
            <tr><td>المبلغ الأساسي</td><td style="text-align:left">{{ number_format($invoice->amount, 2) }} ج.م</td></tr>
            @if($invoice->discount > 0)
            <tr><td>الخصم</td><td class="c-green" style="text-align:left">- {{ number_format($invoice->discount, 2) }} ج.م</td></tr>
            @endif
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
                <tr><th>التاريخ</th><th>المبلغ</th><th>طريقة الدفع</th><th>ملاحظات</th></tr>
            </thead>
            <tbody>
                @foreach($invoice->payments as $p)
                @php $m = ['cash'=>'نقدي','bank_transfer'=>'تحويل بنكي','card'=>'بطاقة','credit_card'=>'بطاقة','check'=>'شيك']; @endphp
                <tr>
                    <td>{{ $p->created_at->format('Y/m/d') }}</td>
                    <td>{{ number_format($p->amount, 2) }} ج.م</td>
                    <td>{{ $m[$p->payment_method] ?? $p->payment_method }}</td>
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

{{-- قسم الشاشة --}}
<div class="invoice-screen">
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('parent.invoices.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right"></i>
            </a>
            <div>
                <h1 class="page-title"><i class="bi bi-receipt me-2 text-primary"></i>{{ $invoice->invoice_number }}</h1>
                <p class="page-subtitle">تفاصيل الفاتورة</p>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-printer me-1"></i>طباعة
        </button>
    </div>
</div>

@php
$statusMap = [
    'paid'      => ['color' => 'success', 'label' => 'مدفوعة'],
    'pending'   => ['color' => 'warning', 'label' => 'معلقة'],
    'overdue'   => ['color' => 'danger',  'label' => 'متأخرة'],
    'partial'   => ['color' => 'info',    'label' => 'جزئية'],
    'cancelled' => ['color' => 'secondary','label' => 'ملغاة'],
];
$s = $statusMap[$invoice->status] ?? ['color' => 'secondary', 'label' => $invoice->status];
@endphp

<div class="row g-4">
    <!-- Invoice Details -->
    <div class="col-lg-7" data-aos="fade-up">
        <div class="glass-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-file-text me-2"></i>تفاصيل الفاتورة</h6>
                <span class="badge bg-{{ $s['color'] }} rounded-pill fs-6">{{ $s['label'] }}</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">رقم الفاتورة</span>
                            <span class="info-value fw-bold">{{ $invoice->invoice_number }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">الطفل</span>
                            <span class="info-value fw-bold">{{ $invoice->child->name }}</span>
                        </div>
                    </div>
                    @if($invoice->feePlan)
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">خطة الرسوم</span>
                            <span class="info-value">{{ $invoice->feePlan->name }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">تاريخ الاستحقاق</span>
                            <span class="info-value fw-bold {{ $invoice->isOverdue() ? 'text-danger' : '' }}">
                                {{ $invoice->due_date->format('Y-m-d') }}
                                @if($invoice->isOverdue())
                                <i class="bi bi-exclamation-triangle-fill text-danger ms-1"></i>
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($invoice->notes)
                    <div class="col-12">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">ملاحظات</span>
                            <span class="info-value">{{ $invoice->notes }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Amount Summary -->
                <div class="mt-4 p-3 rounded" style="background:var(--bg-surface);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">المبلغ الأصلي</span>
                        <span class="fw-bold">{{ number_format($invoice->amount, 2) }} ج.م</span>
                    </div>
                    @if($invoice->discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">الخصم</span>
                        <span class="text-success fw-bold">- {{ number_format($invoice->discount, 2) }} ج.م</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">الإجمالي</span>
                        <span class="fw-bold">{{ number_format($invoice->total, 2) }} ج.م</span>
                    </div>
                    <hr style="border-color:var(--glass-border);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">المدفوع</span>
                        <span class="text-success fw-bold">{{ number_format($invoice->paid_amount, 2) }} ج.م</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">المتبقي</span>
                        <span class="fw-bold {{ $invoice->remaining_amount > 0 ? 'text-danger' : 'text-success' }}" style="font-size:1.1rem;">
                            {{ number_format($invoice->remaining_amount, 2) }} ج.م
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card h-100">
            <div class="card-header">
                <h6><i class="bi bi-cash-coin me-2"></i>المدفوعات</h6>
                <span class="badge bg-success rounded-pill">{{ $invoice->payments->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($invoice->payments->isEmpty())
                <div class="empty-state py-4">
                    <i class="bi bi-cash-coin" style="font-size:2rem;color:var(--text-muted);"></i>
                    <p class="mt-2 text-muted">لا توجد مدفوعات بعد</p>
                </div>
                @else
                <div class="list-group list-group-flush">
                    @foreach($invoice->payments as $payment)
                    <div class="list-group-item" style="background:transparent;border-color:var(--glass-border);">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold text-success">{{ number_format($payment->amount, 2) }} ج.م</div>
                                @if($payment->payment_method)
                                <small class="text-muted">{{ $payment->payment_method }}</small>
                                @endif
                                @if($payment->receiver)
                                <small class="text-muted d-block">{{ $payment->receiver->name }}</small>
                                @endif
                            </div>
                            <small class="text-muted">{{ $payment->created_at->format('Y-m-d') }}</small>
                        </div>
                        @if($payment->notes)
                        <small class="text-muted">{{ $payment->notes }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--glass-border);
}
.info-item:last-child { border-bottom: none; }
</style>
</div>{{-- end invoice-screen --}}
@endsection
