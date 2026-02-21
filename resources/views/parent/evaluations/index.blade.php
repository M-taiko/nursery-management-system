@extends('layouts.app')

@section('page-title', 'تقييمات ' . $child->name)

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parent.children.show', $child) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-clipboard-check me-2 text-primary"></i>تقييمات {{ $child->name }}</h1>
            <p class="page-subtitle">سجل التقييمات الأكاديمية</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('parent.evaluations.index', $child) }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>بحث
                        </button>
                        @if(request()->hasAny(['date','subject_id']))
                        <a href="{{ route('parent.evaluations.index', $child) }}" class="btn btn-outline-secondary">
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
                    <th>المادة</th>
                    <th>مستوى الفهم</th>
                    <th>النسبة</th>
                    <th>السلوك</th>
                    <th>المعلم</th>
                    <th>ملاحظات</th>
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
                    <td>
                        @if($eval->subject->icon)
                        <i class="{{ $eval->subject->icon }} me-1" style="color:{{ $eval->subject->color ?? 'var(--primary)' }};"></i>
                        @endif
                        {{ $eval->subject->name }}
                    </td>
                    <td><span class="badge bg-{{ $ul['color'] }} rounded-pill">{{ $ul['label'] }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-fill" style="height:6px;min-width:60px;">
                                <div class="progress-bar bg-{{ $eval->comprehension_percentage >= 75 ? 'success' : ($eval->comprehension_percentage >= 50 ? 'warning' : 'danger') }}"
                                     style="width:{{ $eval->comprehension_percentage }}%"></div>
                            </div>
                            <small class="fw-bold">{{ $eval->comprehension_percentage }}%</small>
                        </div>
                    </td>
                    <td><span class="badge bg-{{ $bl['color'] }} rounded-pill">{{ $bl['label'] }}</span></td>
                    <td>{{ $eval->teacher->name ?? '-' }}</td>
                    <td>
                        @if($eval->notes)
                        <span class="text-muted" style="font-size:0.85rem;">{{ Str::limit($eval->notes, 40) }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-clipboard-x"></i>
                            <h5>لا توجد تقييمات</h5>
                            <p>لم يتم تسجيل أي تقييمات لهذا الطفل بعد</p>
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
            @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="item-content">
                    <h6>{{ $eval->subject->name }}</h6>
                    <p>{{ $eval->teacher->name ?? '-' }} &bull; {{ $eval->evaluation_date->format('Y-m-d') }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $ul['color'] }} rounded-pill">{{ $ul['label'] }}</span>
                        <small class="text-muted">{{ $eval->comprehension_percentage }}%</small>
                    </div>
                </div>
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
