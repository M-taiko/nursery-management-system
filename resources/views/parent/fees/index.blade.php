@extends('layouts.app')

@section('page-title', 'فواتيري')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-receipt me-2 text-primary"></i>فواتيري</h1>
        <p class="page-subtitle">جميع فواتير الرسوم الدراسية</p>
    </div>
</div>

<!-- Summary Stats -->
@php
    $pending = $invoices->filter(fn($i) => in_array($i->status, ['pending','partial']));
    $totalDue = $pending->sum(fn($i) => $i->remaining_amount);
@endphp
@if($invoices->total() > 0)
<div class="row g-3 mb-4">
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
        <div class="glass-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:1.5rem;">{{ $invoices->total() }}</div>
                    <div class="stat-label">إجمالي الفواتير</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:1.5rem;">{{ $pending->count() }}</div>
                    <div class="stat-label">فواتير معلقة</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon bg-danger">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-value" style="font-size:1.5rem;">{{ number_format($totalDue, 0) }}</div>
                    <div class="stat-label">ج.م المبلغ المستحق</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="50">
    <div class="card-header">
        <h6><i class="bi bi-list-ul me-2"></i>قائمة الفواتير</h6>
        <span class="badge bg-primary rounded-pill">{{ $invoices->total() }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>الطفل</th>
                    <th>المبلغ الكلي</th>
                    <th>المدفوع</th>
                    <th>المتبقي</th>
                    <th>تاريخ الاستحقاق</th>
                    <th>الحالة</th>
                    <th class="text-center">التفاصيل</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                @php
                    $statusMap = [
                        'paid'    => ['color'=>'success','label'=>'مدفوعة'],
                        'pending' => ['color'=>'warning','label'=>'معلقة'],
                        'overdue' => ['color'=>'danger','label'=>'متأخرة'],
                        'partial' => ['color'=>'info','label'=>'جزئية'],
                        'cancelled' => ['color'=>'secondary','label'=>'ملغاة'],
                    ];
                    $s = $statusMap[$invoice->status] ?? ['color'=>'secondary','label'=>$invoice->status];
                    $overdue = $invoice->isOverdue();
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('parent.invoices.show', $invoice) }}" class="text-primary fw-bold">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td>{{ $invoice->child->name }}</td>
                    <td class="fw-bold">{{ number_format($invoice->total, 2) }} ج.م</td>
                    <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} ج.م</td>
                    <td class="{{ $invoice->remaining_amount > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                        {{ number_format($invoice->remaining_amount, 2) }} ج.م
                    </td>
                    <td class="{{ $overdue ? 'text-danger' : '' }}">
                        {{ $invoice->due_date->format('Y-m-d') }}
                        @if($overdue)
                        <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="متأخرة"></i>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $s['color'] }} rounded-pill">{{ $s['label'] }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('parent.invoices.show', $invoice) }}"
                           class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-receipt"></i>
                            <h5>لا توجد فواتير</h5>
                            <p>لم يتم إنشاء أي فواتير لأطفالك بعد</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="d-md-none p-3">
        <div class="card-list">
            @forelse($invoices as $invoice)
            @php
                $statusMap = ['paid'=>['color'=>'success','label'=>'مدفوعة'],'pending'=>['color'=>'warning','label'=>'معلقة'],'overdue'=>['color'=>'danger','label'=>'متأخرة'],'partial'=>['color'=>'info','label'=>'جزئية'],'cancelled'=>['color'=>'secondary','label'=>'ملغاة']];
                $s = $statusMap[$invoice->status] ?? ['color'=>'secondary','label'=>$invoice->status];
                $overdue = $invoice->isOverdue();
            @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="item-content">
                    <h6>{{ $invoice->child->name }}</h6>
                    <p>{{ $invoice->invoice_number }} &bull; {{ $invoice->due_date->format('Y-m-d') }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $s['color'] }} rounded-pill">{{ $s['label'] }}</span>
                        <small class="{{ $invoice->remaining_amount > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                            متبقي: {{ number_format($invoice->remaining_amount, 0) }} ج.م
                        </small>
                    </div>
                </div>
                <a href="{{ route('parent.invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-receipt"></i>
                <h5>لا توجد فواتير</h5>
                <p>لم يتم إنشاء أي فواتير لأطفالك بعد</p>
            </div>
            @endforelse
        </div>
    </div>

    @if($invoices->hasPages())
    <div class="card-footer">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
