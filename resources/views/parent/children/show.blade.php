@extends('layouts.app')

@section('page-title', $child->name)

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parent.children.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-person me-2 text-primary"></i>{{ $child->name }}</h1>
            <p class="page-subtitle">الملف الشخصي للطفل</p>
        </div>
    </div>
</div>

<!-- Child Profile Card -->
<div class="row g-4 mb-4">
    <div class="col-lg-4" data-aos="fade-up">
        <div class="glass-card h-100">
            <div class="card-body text-center p-4">
                <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                     class="rounded-circle mb-3"
                     style="width:110px;height:110px;object-fit:cover;border:3px solid var(--glass-border);">
                <h4 class="fw-bold mb-1">{{ $child->name }}</h4>
                <p class="text-muted mb-3" style="font-size:0.9rem;">
                    {{ $child->stage->name }} &bull; {{ $child->classroom->name }}
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                    <span class="badge bg-primary rounded-pill">{{ $child->age }}</span>
                    <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                        {{ $child->status_label }}
                    </span>
                    @if($child->photo_consent)
                    <span class="badge bg-info rounded-pill">موافق على التصوير</span>
                    @endif
                </div>
                <!-- Quick Actions -->
                <div class="d-grid gap-2">
                    <a href="{{ route('parent.evaluations.index', $child) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-clipboard-check me-1"></i>التقييمات
                    </a>
                    @if($child->photo_consent)
                    <a href="{{ route('parent.photos.index', $child) }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-images me-1"></i>الصور
                    </a>
                    @endif
                    <a href="{{ route('parent.behavior.index', $child) }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-emoji-smile me-1"></i>سجل السلوك
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card h-100">
            <div class="card-header">
                <h6><i class="bi bi-info-circle me-2"></i>المعلومات الأساسية</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">الاسم الكامل</span>
                            <span class="info-value fw-bold">{{ $child->name }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">العمر</span>
                            <span class="info-value fw-bold">{{ $child->age }}</span>
                        </div>
                    </div>
                    @if($child->birth_date)
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">تاريخ الميلاد</span>
                            <span class="info-value fw-bold">{{ $child->birth_date->format('Y-m-d') }}</span>
                        </div>
                    </div>
                    @endif
                    @if($child->gender)
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">الجنس</span>
                            <span class="info-value fw-bold">
                                {{ $child->gender === 'male' ? 'ذكر' : 'أنثى' }}
                            </span>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">المرحلة</span>
                            <span class="info-value fw-bold">{{ $child->stage->name }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">الفصل</span>
                            <span class="info-value fw-bold">{{ $child->classroom->name }}</span>
                        </div>
                    </div>
                    @if($child->medical_notes)
                    <div class="col-12">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">ملاحظات طبية</span>
                            <span class="info-value">{{ $child->medical_notes }}</span>
                        </div>
                    </div>
                    @endif
                    @if($child->notes)
                    <div class="col-12">
                        <div class="info-item">
                            <span class="info-label text-muted" style="font-size:0.8rem;">ملاحظات</span>
                            <span class="info-value">{{ $child->notes }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div x-data="{ activeTab: 'evaluations' }">
    <div class="glass-card glass-card-no-hover mb-3" data-aos="fade-up">
        <div class="card-body p-2">
            <div class="d-flex gap-2">
                <button @click="activeTab = 'evaluations'"
                        :class="activeTab === 'evaluations' ? 'btn-primary' : 'btn-outline-secondary'"
                        class="btn btn-sm flex-fill">
                    <i class="bi bi-clipboard-check me-1"></i>آخر التقييمات
                </button>
                <button @click="activeTab = 'behavior'"
                        :class="activeTab === 'behavior' ? 'btn-primary' : 'btn-outline-secondary'"
                        class="btn btn-sm flex-fill">
                    <i class="bi bi-emoji-smile me-1"></i>سجل السلوك
                </button>
            </div>
        </div>
    </div>

    <!-- Evaluations Tab -->
    <div x-show="activeTab === 'evaluations'" x-transition data-aos="fade-up">
        <div class="glass-card glass-card-no-hover">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-clipboard-check me-2"></i>آخر التقييمات</h6>
                <a href="{{ route('parent.evaluations.index', $child) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>المادة</th>
                            <th>مستوى الفهم</th>
                            <th>المعلم</th>
                            <th>التاريخ</th>
                            @if(false)<th>ملاحظات</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($child->evaluations as $eval)
                        <tr>
                            <td>
                                @if($eval->subject->icon)
                                <i class="{{ $eval->subject->icon }} me-1" style="color:{{ $eval->subject->color ?? 'var(--primary)' }};"></i>
                                @endif
                                {{ $eval->subject->name }}
                            </td>
                            <td>
                                <span class="badge-level {{ $eval->understanding_level }}">
                                    {{ $eval->understanding_label }}
                                </span>
                            </td>
                            <td>{{ $eval->teacher->name ?? '-' }}</td>
                            <td>{{ $eval->evaluation_date->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="bi bi-clipboard"></i>
                                    <h5>لا توجد تقييمات</h5>
                                    <p>لم يتم تسجيل أي تقييمات بعد</p>
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
                    @forelse($child->evaluations as $eval)
                    <div class="list-item">
                        <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                            <i class="{{ $eval->subject->icon ?? 'bi bi-book' }}"></i>
                        </div>
                        <div class="item-content">
                            <h6>{{ $eval->subject->name }}</h6>
                            <p>{{ $eval->teacher->name ?? '-' }} &bull; {{ $eval->evaluation_date->format('Y-m-d') }}</p>
                            <div class="item-meta">
                                <span class="badge-level {{ $eval->understanding_level }}">
                                    {{ $eval->understanding_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-clipboard"></i>
                        <h5>لا توجد تقييمات</h5>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Behavior Tab -->
    <div x-show="activeTab === 'behavior'" x-transition data-aos="fade-up">
        <div class="glass-card glass-card-no-hover">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="bi bi-emoji-smile me-2"></i>سجل السلوك</h6>
                <a href="{{ route('parent.behavior.index', $child) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>النوع</th>
                            <th>الفئة</th>
                            <th>الوصف</th>
                            <th>المعلم</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($child->behaviorRecords as $record)
                        @php
                            $typeColor = ['positive'=>'success','negative'=>'danger','neutral'=>'secondary'][$record->type] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-{{ $typeColor }} rounded-pill">{{ $record->type_label }}</span>
                            </td>
                            <td>{{ $record->category ?? '-' }}</td>
                            <td>{{ Str::limit($record->description, 60) }}</td>
                            <td>{{ $record->teacher->name ?? '-' }}</td>
                            <td>{{ $record->record_date->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-emoji-smile"></i>
                                    <h5>لا توجد سجلات سلوك</h5>
                                    <p>لم يتم تسجيل أي سلوك بعد</p>
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
                    @forelse($child->behaviorRecords as $record)
                    @php
                        $typeColor = ['positive'=>'success','negative'=>'danger','neutral'=>'secondary'][$record->type] ?? 'secondary';
                    @endphp
                    <div class="list-item">
                        <div style="width:42px;height:42px;border-radius:12px;background:var(--bg-surface);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span class="badge bg-{{ $typeColor }}">
                                <i class="bi bi-{{ $record->type === 'positive' ? 'emoji-smile' : ($record->type === 'negative' ? 'emoji-frown' : 'emoji-neutral') }}"></i>
                            </span>
                        </div>
                        <div class="item-content">
                            <h6>{{ $record->type_label }} @if($record->category)&bull; {{ $record->category }}@endif</h6>
                            <p>{{ Str::limit($record->description, 70) }}</p>
                            <div class="item-meta">
                                <small class="text-muted">{{ $record->teacher->name ?? '-' }}</small>
                                <small class="text-muted">{{ $record->record_date->format('Y-m-d') }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-emoji-smile"></i>
                        <h5>لا توجد سجلات سلوك</h5>
                    </div>
                    @endforelse
                </div>
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
