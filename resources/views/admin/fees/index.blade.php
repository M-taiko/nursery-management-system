@extends('layouts.app')

@section('page-title', 'المصروفات والفواتير')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-cash-stack me-2 text-primary"></i>المصروفات والفواتير</h1>
        <p class="page-subtitle">إدارة فواتير الرسوم الدراسية والمدفوعات</p>
    </div>
    <a href="{{ route('admin.fees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>إنشاء فاتورة
    </a>
</div>

<!-- Filters -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.fees.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">الطفل</label>
                    <select name="child_id" class="form-select">
                        <option value="">جميع الأطفال</option>
                        @foreach($children as $child)
                        <option value="{{ $child->id }}" {{ request('child_id') == $child->id ? 'selected' : '' }}>
                            {{ $child->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>مدفوع</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>متأخر</option>
                        <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>جزئي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>بحث
                        </button>
                        @if(request()->hasAny(['child_id','status']))
                        <a href="{{ route('admin.fees.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('admin.fees.plans') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-calculator me-1"></i>خطط الرسوم
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="50">
    <div class="card-header">
        <h6><i class="bi bi-receipt me-2"></i>قائمة الفواتير</h6>
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
                    <th>الاستحقاق</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                @php
                    $statusMap = [
                        'paid'    => ['color'=>'success','label'=>'مدفوع'],
                        'pending' => ['color'=>'warning','label'=>'معلق'],
                        'overdue' => ['color'=>'danger','label'=>'متأخر'],
                        'partial' => ['color'=>'info','label'=>'جزئي'],
                    ];
                    $s = $statusMap[$invoice->status] ?? ['color'=>'secondary','label'=>$invoice->status];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.fees.show', $invoice) }}" class="text-primary fw-600">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td>{{ $invoice->child->name }}</td>
                    <td class="fw-600">{{ number_format($invoice->amount, 2) }} ج.م</td>
                    <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} ج.م</td>
                    <td class="{{ $invoice->remaining_amount > 0 ? 'text-danger fw-600' : 'text-success' }}">
                        {{ number_format($invoice->remaining_amount, 2) }} ج.م
                    </td>
                    <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge bg-{{ $s['color'] }} rounded-pill">{{ $s['label'] }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.fees.show', $invoice) }}"
                               class="btn btn-sm btn-outline-info" title="عرض">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($invoice->status !== 'paid')
                            <a href="{{ route('admin.fees.show', $invoice) }}#payment-form"
                               class="btn btn-sm btn-outline-success" title="إضافة دفعة">
                                <i class="bi bi-cash"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-receipt"></i>
                            <h5>لا توجد فواتير</h5>
                            <p>لم يتم إنشاء أي فواتير بعد</p>
                            <a href="{{ route('admin.fees.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>إنشاء فاتورة
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile -->
    <div class="d-md-none p-3">
        <div class="card-list">
            @forelse($invoices as $invoice)
            @php
                $statusMap = ['paid'=>['color'=>'success','label'=>'مدفوع'],'pending'=>['color'=>'warning','label'=>'معلق'],'overdue'=>['color'=>'danger','label'=>'متأخر'],'partial'=>['color'=>'info','label'=>'جزئي']];
                $s = $statusMap[$invoice->status] ?? ['color'=>'secondary','label'=>$invoice->status];
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
                        <small class="text-muted">{{ number_format($invoice->amount, 2) }} ج.م</small>
                    </div>
                </div>
                <a href="{{ route('admin.fees.show', $invoice) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-receipt"></i>
                <h5>لا توجد فواتير</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($invoices->hasPages())
    <div class="card-footer">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
