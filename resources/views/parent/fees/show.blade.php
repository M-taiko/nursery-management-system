@extends('layouts.app')

@section('page-title', 'فاتورة ' . $invoice->invoice_number)

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parent.invoices.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-receipt me-2 text-primary"></i>{{ $invoice->invoice_number }}</h1>
            <p class="page-subtitle">تفاصيل الفاتورة</p>
        </div>
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
@endsection
