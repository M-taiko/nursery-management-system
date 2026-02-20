@extends('layouts.app')

@section('page-title', 'لوحة تحكم المعلم')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <h2 class="page-title">
        <i class="bi bi-speedometer2 me-2"></i>لوحة تحكم المعلم
    </h2>
    <p class="page-subtitle">مرحباً {{ auth()->user()->name }}، إليك ملخص يومك</p>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="0">
            <div class="stat-icon bg-primary">
                <i class="bi bi-door-open"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $data['my_classrooms'] }}</h3>
                <p class="stat-label">فصولي</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-icon bg-success">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $data['my_students'] }}</h3>
                <p class="stat-label">طلابي</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-icon bg-info">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $data['today_evaluations'] }}</h3>
                <p class="stat-label">تقييمات اليوم</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-icon bg-warning">
                <i class="bi bi-camera"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $data['uploaded_photos'] }}</h3>
                <p class="stat-label">الصور المرفوعة</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-lightning-charge me-2"></i>إجراءات سريعة</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('teacher.evaluations.create') }}" class="btn btn-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
                    <i class="bi bi-plus-circle fs-4"></i>
                    <span class="small">إضافة تقييم</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('teacher.photos.create') }}" class="btn btn-success w-100 py-3 d-flex flex-column align-items-center gap-2">
                    <i class="bi bi-camera fs-4"></i>
                    <span class="small">رفع صور</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('teacher.behavior.create') }}" class="btn btn-warning w-100 py-3 d-flex flex-column align-items-center gap-2">
                    <i class="bi bi-emoji-smile fs-4"></i>
                    <span class="small">تسجيل سلوك</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('teacher.evaluations.daily-report') }}" class="btn btn-info w-100 py-3 d-flex flex-column align-items-center gap-2">
                    <i class="bi bi-file-earmark-bar-graph fs-4"></i>
                    <span class="small">التقرير اليومي</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row g-4">
    <!-- My Classrooms -->
    <div class="col-lg-5">
        <div class="glass-card glass-card-no-hover h-100" data-aos="fade-up" data-aos-delay="50">
            <div class="card-header">
                <h6><i class="bi bi-door-open me-2"></i>فصولي الدراسية</h6>
                <a href="{{ route('teacher.classrooms.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @forelse($data['classrooms'] as $classroom)
                @php
                    $fillPct = min(100, ($classroom->children_count / max(1,$classroom->capacity)) * 100);
                    $c = $fillPct >= 100 ? 'danger' : ($fillPct >= 75 ? 'warning' : 'success');
                @endphp
                <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3" style="background:var(--glass-backdrop);">
                    <div>
                        <h6 class="mb-1 fw-600">{{ $classroom->name }}</h6>
                        <small class="text-muted">{{ $classroom->stage->name }}</small>
                    </div>
                    <span class="badge bg-{{ $c }} rounded-pill">{{ $classroom->children_count }} طفل</span>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-door-closed"></i>
                    <p>لا توجد فصول مخصصة</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Evaluations -->
    <div class="col-lg-7">
        <div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <h6><i class="bi bi-clipboard-data me-2"></i>آخر التقييمات</h6>
                <a href="{{ route('teacher.evaluations.index') }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
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
                        @php
                        $levelMap = [
                            'excellent' => 'success', 'very_good' => 'primary',
                            'good' => 'info', 'average' => 'warning', 'needs_improvement' => 'danger',
                        ];
                        @endphp
                        @forelse($data['recent_evaluations'] as $eval)
                        @php $lc = $levelMap[$eval->understanding_level] ?? 'secondary'; @endphp
                        <tr>
                            <td><strong>{{ $eval->child->name }}</strong></td>
                            <td>{{ $eval->subject->name }}</td>
                            <td><span class="badge bg-{{ $lc }} rounded-pill">{{ $eval->understanding_label }}</span></td>
                            <td>{{ $eval->evaluation_date->format('Y-m-d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state py-4">
                                    <i class="bi bi-clipboard-x"></i>
                                    <p>لا توجد تقييمات</p>
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
@endsection
