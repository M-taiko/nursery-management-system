@extends('layouts.app')

@section('page-title', 'تقييماتي')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-clipboard-check me-2 text-primary"></i>تقييماتي</h1>
        <p class="page-subtitle">إدارة تقييمات الأطفال اليومية</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.evaluations.daily-report') }}" class="btn btn-outline-info">
            <i class="bi bi-file-earmark-bar-graph me-1"></i>التقرير اليومي
        </a>
        <a href="{{ route('teacher.evaluations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>إضافة تقييم
        </a>
    </div>
</div>

<!-- Filters -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.evaluations.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الفصل</label>
                    <select name="classroom_id" class="form-select">
                        <option value="">جميع الفصول</option>
                        @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">المادة</label>
                    <select name="subject_id" class="form-select">
                        <option value="">جميع المواد</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>بحث
                        </button>
                        @if(request()->hasAny(['date','classroom_id','subject_id']))
                        <a href="{{ route('teacher.evaluations.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="50">
    <div class="card-header">
        <h6><i class="bi bi-clipboard-data me-2"></i>قائمة التقييمات</h6>
        <span class="badge bg-primary rounded-pill">{{ $evaluations->total() }}</span>
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

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>الطفل</th>
                    <th>الفصل</th>
                    <th>المادة</th>
                    <th>مستوى الفهم</th>
                    <th>النسبة</th>
                    <th>السلوك</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eval)
                @php
                    $ul = $levelMap[$eval->understanding_level] ?? ['label' => $eval->understanding_level, 'color' => 'secondary'];
                    $bl = $levelMap[$eval->behavior] ?? ['label' => $eval->behavior, 'color' => 'secondary'];
                @endphp
                <tr>
                    <td>{{ $eval->evaluation_date->format('Y-m-d') }}</td>
                    <td><strong>{{ $eval->child->name }}</strong></td>
                    <td>{{ $eval->child->classroom->name }}</td>
                    <td>{{ $eval->subject->name }}</td>
                    <td><span class="badge bg-{{ $ul['color'] }} rounded-pill">{{ $ul['label'] }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-fill" style="height:6px;">
                                <div class="progress-bar bg-{{ $eval->comprehension_percentage >= 75 ? 'success' : ($eval->comprehension_percentage >= 50 ? 'warning' : 'danger') }}"
                                     style="width:{{ $eval->comprehension_percentage }}%"></div>
                            </div>
                            <small class="text-muted fw-600">{{ $eval->comprehension_percentage }}%</small>
                        </div>
                    </td>
                    <td><span class="badge bg-{{ $bl['color'] }} rounded-pill">{{ $bl['label'] }}</span></td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('teacher.evaluations.edit', $eval) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-clipboard-x"></i>
                            <h5>لا توجد تقييمات</h5>
                            <p>لم تقم بإضافة أي تقييمات بعد</p>
                            <a href="{{ route('teacher.evaluations.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>إضافة تقييم
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
            @forelse($evaluations as $eval)
            @php
                $ul = $levelMap[$eval->understanding_level] ?? ['label' => $eval->understanding_level, 'color' => 'secondary'];
                $bl = $levelMap[$eval->behavior] ?? ['label' => $eval->behavior, 'color' => 'secondary'];
            @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="item-content">
                    <h6>{{ $eval->child->name }}</h6>
                    <p>{{ $eval->subject->name }} &bull; {{ $eval->evaluation_date->format('Y-m-d') }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $ul['color'] }} rounded-pill">{{ $ul['label'] }}</span>
                        <small class="text-muted">{{ $eval->comprehension_percentage }}%</small>
                    </div>
                </div>
                <a href="{{ route('teacher.evaluations.edit', $eval) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-clipboard-x"></i>
                <h5>لا توجد تقييمات</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($evaluations->hasPages())
    <div class="card-footer">{{ $evaluations->links() }}</div>
    @endif
</div>
@endsection
