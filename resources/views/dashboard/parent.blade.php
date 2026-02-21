@extends('layouts.app')

@section('page-title', 'لوحة تحكم ولي الأمر')

@section('content')
<!-- My Children -->
<div class="row g-4 mb-4">
    @forelse($data['children'] as $child)
    <div class="col-md-6 col-lg-4">
        <div class="card custom-card h-100">
            <div class="card-body text-center">
                <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                     class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                <h5 class="mb-2">{{ $child->name }}</h5>
                <p class="text-muted mb-2">{{ $child->stage->name }} - {{ $child->classroom->name }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-primary">{{ $child->age }} سنوات</span>
                    <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }}">
                        {{ $child->status_label }}
                    </span>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('parent.children.show', $child) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye me-1"></i>عرض التفاصيل
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>لا يوجد أطفال مسجلين
        </div>
    </div>
    @endforelse
</div>

@if($data['children']->isNotEmpty())
<!-- Stats Overview -->
<div class="row g-3 mb-4">
    <!-- Total Evaluations -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $data['total_evaluations'] }}</div>
                    <div class="stat-label">إجمالي التقييمات</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Photos -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success">
                    <i class="bi bi-images"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $data['total_photos'] }}</div>
                    <div class="stat-label">الصور المتاحة</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Invoices -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $data['pending_invoices'] }}</div>
                    <div class="stat-label">فواتير معلقة</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Due Amount -->
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($data['total_due'], 0) }}</div>
                    <div class="stat-label">ج.م المبلغ المستحق</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data -->
<div class="row g-4">
    <!-- Recent Evaluations -->
    <div class="col-lg-6">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>آخر التقييمات</h5>
                @if($data['children']->isNotEmpty())
                <a href="{{ route('parent.evaluations.index', $data['children']->first()) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>الطفل</th>
                                <th>المادة</th>
                                <th>المستوى</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recent_evaluations'] as $eval)
                            <tr>
                                <td>{{ $eval->child->name }}</td>
                                <td>{{ $eval->subject->name }}</td>
                                <td><span class="badge-level {{ $eval->understanding_level }}">{{ $eval->understanding_label }}</span></td>
                                <td>{{ $eval->evaluation_date->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">لا توجد تقييمات</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Photos -->
    <div class="col-lg-6">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-images me-2"></i>أحدث الصور</h5>
                @if($data['children']->isNotEmpty())
                <a href="{{ route('parent.photos.index', $data['children']->first()) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @forelse($data['recent_photos'] as $photo)
                    <div class="col-4">
                        <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo->thumbnail_path) }}"
                                 alt="{{ $photo->description }}"
                                 class="img-thumbnail"
                                 style="width: 100%; height: 100px; object-fit: cover;">
                        </a>
                        <small class="text-muted d-block text-center mt-1">
                            {{ $photo->photo_date->format('Y-m-d') }}
                        </small>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">لا توجد صور</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Invoices -->
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>الفواتير المعلقة</h5>
                <a href="{{ route('parent.invoices.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>الطفل</th>
                                <th>المبلغ</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['pending_invoices_list'] as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->child->name }}</td>
                                <td>{{ number_format($invoice->amount, 2) }} ج.م</td>
                                <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->isOverdue() ? 'danger' : 'warning' }}">
                                        {{ $invoice->isOverdue() ? 'متأخر' : 'معلق' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">لا توجد فواتير معلقة</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
