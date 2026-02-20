@extends('layouts.app')

@section('page-title', 'ملف الطفل')

@section('content')
<!-- Hero Header -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-down">
    <div class="card-body">
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                 class="rounded-circle flex-shrink-0"
                 style="width:90px;height:90px;object-fit:cover;border:3px solid var(--primary);box-shadow:0 0 0 4px rgba(var(--primary-rgb),0.15)"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($child->name) }}&background=6366f1&color=fff&size=90'">
            <div class="flex-grow-1">
                <h3 class="mb-1 fw-700">{{ $child->name }}</h3>
                @if($child->national_id)
                <p class="text-muted small mb-2"><i class="bi bi-credit-card me-1"></i>{{ $child->national_id }}</p>
                @endif
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-primary rounded-pill">{{ $child->age }} سنة</span>
                    @php
                        $statusColors = ['active'=>'success','inactive'=>'secondary','graduated'=>'primary'];
                        $statusLabels = ['active'=>'نشط','inactive'=>'غير نشط','graduated'=>'متخرج'];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$child->status] ?? 'secondary' }} rounded-pill">
                        {{ $statusLabels[$child->status] ?? $child->status }}
                    </span>
                    <span class="badge bg-info rounded-pill">
                        {{ $child->gender === 'male' ? 'ذكر' : 'أنثى' }}
                    </span>
                    @if($child->blood_type)
                    <span class="badge bg-danger rounded-pill">{{ $child->blood_type }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="{{ route('admin.children.edit', $child) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i>تعديل
                </a>
                <a href="{{ route('admin.children.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>رجوع
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Info Cards Row -->
<div class="row g-4 mb-4">
    <!-- Basic Information -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="50">
        <div class="glass-card glass-card-no-hover h-100">
            <div class="card-header">
                <h6><i class="bi bi-info-circle me-2 text-primary"></i>المعلومات الأساسية</h6>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label text-muted small">تاريخ الميلاد</span>
                    <span class="info-value">{{ $child->birth_date?->format('Y-m-d') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label text-muted small">المرحلة</span>
                    <span class="info-value">{{ $child->stage->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label text-muted small">الفصل</span>
                    <span class="info-value">{{ $child->classroom->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label text-muted small">تاريخ الالتحاق</span>
                    <span class="info-value">{{ $child->enrollment_date?->format('Y-m-d') ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label text-muted small">موافقة الصور</span>
                    <span class="info-value">
                        @if($child->photo_consent)
                        <span class="badge bg-success"><i class="bi bi-check"></i> نعم</span>
                        @else
                        <span class="badge bg-danger"><i class="bi bi-x"></i> لا</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Parent Information -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card glass-card-no-hover h-100">
            <div class="card-header">
                <h6><i class="bi bi-person-badge me-2 text-primary"></i>ولي الأمر</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;font-size:1.25rem;">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <div class="fw-600">{{ $child->parent->name }}</div>
                        <small class="text-muted">ولي أمر</small>
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label text-muted small"><i class="bi bi-envelope me-1"></i>البريد</span>
                    <span class="info-value small">{{ $child->parent->email }}</span>
                </div>
                @if($child->parent->phone)
                <div class="info-row">
                    <span class="info-label text-muted small"><i class="bi bi-phone me-1"></i>الهاتف</span>
                    <span class="info-value">{{ $child->parent->phone }}</span>
                </div>
                @endif
                @if($child->emergency_contact)
                <div class="info-row">
                    <span class="info-label text-muted small"><i class="bi bi-telephone-forward me-1"></i>طوارئ</span>
                    <span class="info-value">{{ $child->emergency_contact }} - {{ $child->emergency_phone }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Medical Information -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="150">
        <div class="glass-card glass-card-no-hover h-100">
            <div class="card-header">
                <h6><i class="bi bi-heart-pulse me-2 text-danger"></i>المعلومات الطبية</h6>
            </div>
            <div class="card-body">
                @if($child->blood_type)
                <div class="info-row">
                    <span class="info-label text-muted small">فصيلة الدم</span>
                    <span class="badge bg-danger">{{ $child->blood_type }}</span>
                </div>
                @endif
                @if($child->allergies)
                <div class="mb-3">
                    <div class="text-muted small mb-1">الحساسيات</div>
                    <p class="small bg-danger bg-opacity-10 text-danger p-2 rounded mb-0">{{ $child->allergies }}</p>
                </div>
                @endif
                <div>
                    <div class="text-muted small mb-1">ملاحظات طبية</div>
                    <p class="small mb-0">{{ $child->medical_notes ?? 'لا توجد ملاحظات' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Evaluations -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="200">
    <div class="card-header">
        <h6><i class="bi bi-clipboard-check me-2 text-primary"></i>آخر التقييمات</h6>
        <span class="badge bg-primary rounded-pill">{{ $child->evaluations->count() }}</span>
    </div>
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المادة</th>
                    <th>المستوى</th>
                    <th>النسبة</th>
                    <th>السلوك</th>
                    <th>المعلم</th>
                </tr>
            </thead>
            <tbody>
                @forelse($child->evaluations->take(10) as $eval)
                <tr>
                    <td>{{ $eval->evaluation_date->format('Y-m-d') }}</td>
                    <td>{{ $eval->subject->name }}</td>
                    <td><span class="badge-level {{ $eval->understanding_level }}">{{ $eval->understanding_label }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;max-width:80px;">
                                <div class="progress-bar bg-primary" style="width:{{ $eval->comprehension_percentage }}%"></div>
                            </div>
                            <small>{{ $eval->comprehension_percentage }}%</small>
                        </div>
                    </td>
                    <td><span class="badge-level {{ $eval->behavior }}">{{ $eval->behavior_label }}</span></td>
                    <td>{{ $eval->teacher->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state py-3">
                            <i class="bi bi-clipboard" style="font-size:2rem;"></i>
                            <p class="mb-0">لا توجد تقييمات</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Photos -->
@if($child->photos->count() > 0)
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="250">
    <div class="card-header">
        <h6><i class="bi bi-images me-2 text-primary"></i>أحدث الصور</h6>
        <span class="badge bg-primary rounded-pill">{{ $child->photos->count() }}</span>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @foreach($child->photos->take(12) as $photo)
            <div class="col-4 col-md-2">
                <div class="photo-card" style="height:100px;">
                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . ($photo->thumbnail_path ?? $photo->photo_path)) }}"
                             alt="{{ $photo->description }}"
                             loading="lazy">
                        <div class="photo-overlay">
                            <i class="bi bi-zoom-in"></i>
                        </div>
                    </a>
                </div>
                <small class="text-muted d-block text-center mt-1">
                    {{ $photo->photo_date->format('m/d') }}
                </small>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Fee Invoices -->
<div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="300">
    <div class="card-header">
        <h6><i class="bi bi-receipt me-2 text-primary"></i>الفواتير</h6>
        <a href="{{ route('admin.fees.index', ['child_id' => $child->id]) }}" class="btn btn-sm btn-outline-primary">
            عرض الكل
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>المبلغ الكلي</th>
                    <th>المدفوع</th>
                    <th>المتبقي</th>
                    <th>الاستحقاق</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($child->feeInvoices->take(5) as $invoice)
                <tr>
                    <td>
                        <a href="{{ route('admin.fees.show', $invoice) }}" class="text-primary fw-600">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td>{{ number_format($invoice->amount, 2) }} ج.م</td>
                    <td class="text-success">{{ number_format($invoice->paid_amount, 2) }} ج.م</td>
                    <td class="{{ $invoice->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($invoice->remaining_amount, 2) }} ج.م
                    </td>
                    <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                    <td>
                        @php
                            $invColors = ['paid'=>'success','pending'=>'warning','overdue'=>'danger','partial'=>'info'];
                            $invLabels = ['paid'=>'مدفوع','pending'=>'معلق','overdue'=>'متأخر','partial'=>'جزئي'];
                        @endphp
                        <span class="badge bg-{{ $invColors[$invoice->status] ?? 'secondary' }} rounded-pill">
                            {{ $invLabels[$invoice->status] ?? $invoice->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state py-3">
                            <i class="bi bi-receipt" style="font-size:2rem;"></i>
                            <p class="mb-0">لا توجد فواتير</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--glass-border);
}
.info-row:last-child { border-bottom: none; }
.info-value { font-weight: 500; }
</style>
@endpush
