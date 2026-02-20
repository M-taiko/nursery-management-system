@extends('layouts.app')

@section('page-title', 'التقرير اليومي')

@push('styles')
<style>
@media print {
    .sidebar, .top-navbar, .app-background, .sidebar-overlay,
    .no-print, .btn, .card-footer { display: none !important; }
    body, html { margin: 0; padding: 0; }
    .app-wrapper, .main-content, .container-fluid {
        display: block !important; width: 100% !important;
        margin: 0 !important; padding: 0 !important;
    }
    @page { margin: 14mm; size: A4; }
}
</style>
@endpush

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i>التقرير اليومي</h1>
        <p class="page-subtitle">تقرير تقييمات الأطفال حسب الفصل والتاريخ</p>
    </div>
    <div class="d-flex gap-2 no-print">
        @if(!empty($report) && $report->count() > 0)
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer me-1"></i>طباعة
        </button>
        @endif
        <a href="{{ route('teacher.evaluations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i>التقييمات
        </a>
    </div>
</div>

<!-- Filter -->
<div class="glass-card glass-card-no-hover mb-4 no-print" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.evaluations.daily-report') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">الفصل</label>
                    <select name="classroom_id" class="form-select" onchange="this.form.submit()">
                        <option value="">اختر الفصل...</option>
                        @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ $classroomId == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }} — {{ $classroom->stage->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control"
                           value="{{ $date }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>عرض التقرير
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
$levelMap = [
    'excellent'         => ['label' => 'ممتاز',       'color' => 'success'],
    'very_good'         => ['label' => 'جيد جداً',    'color' => 'primary'],
    'good'              => ['label' => 'جيد',          'color' => 'info'],
    'average'           => ['label' => 'مقبول',        'color' => 'warning'],
    'needs_improvement' => ['label' => 'يحتاج تحسين', 'color' => 'danger'],
];
@endphp

@if(!empty($report) && $report->count() > 0)
    @foreach($report as $childId => $evaluations)
    @php $child = $evaluations->first()->child; @endphp
    <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
        <div class="card-header">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                     class="rounded-circle" style="width:42px;height:42px;object-fit:cover;border:2px solid var(--glass-border);">
                <div>
                    <h6 class="mb-0">{{ $child->name }}</h6>
                    <small class="text-muted">{{ $child->classroom->name }}</small>
                </div>
            </div>
            <span class="badge bg-primary rounded-pill">{{ $evaluations->count() }} مادة</span>
        </div>
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>المادة</th>
                        <th>مستوى الفهم</th>
                        <th>النسبة</th>
                        <th>السلوك</th>
                        <th>الملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluations as $eval)
                    @php
                        $ul = $levelMap[$eval->understanding_level] ?? ['label' => $eval->understanding_level, 'color' => 'secondary'];
                        $bl = $levelMap[$eval->behavior] ?? ['label' => $eval->behavior, 'color' => 'secondary'];
                    @endphp
                    <tr>
                        <td><strong>{{ $eval->subject->name }}</strong></td>
                        <td><span class="badge bg-{{ $ul['color'] }} rounded-pill">{{ $ul['label'] }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-fill" style="height:6px;min-width:60px;">
                                    <div class="progress-bar bg-{{ $eval->comprehension_percentage >= 75 ? 'success' : ($eval->comprehension_percentage >= 50 ? 'warning' : 'danger') }}"
                                         style="width:{{ $eval->comprehension_percentage }}%"></div>
                                </div>
                                <small class="text-muted fw-600">{{ $eval->comprehension_percentage }}%</small>
                            </div>
                        </td>
                        <td><span class="badge bg-{{ $bl['color'] }} rounded-pill">{{ $bl['label'] }}</span></td>
                        <td class="text-muted small">{{ $eval->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
@else
<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-body">
        <div class="empty-state">
            <i class="bi bi-file-earmark-bar-graph"></i>
            @if(empty($classroomId))
            <h5>اختر الفصل والتاريخ</h5>
            <p>حدد الفصل والتاريخ لعرض التقرير اليومي</p>
            @else
            <h5>لا توجد تقييمات</h5>
            <p>لا توجد تقييمات مسجلة لهذا اليوم</p>
            @endif
        </div>
    </div>
</div>
@endif
@endsection
