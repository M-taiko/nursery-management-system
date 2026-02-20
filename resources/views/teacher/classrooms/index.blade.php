@extends('layouts.app')

@section('page-title', 'فصولي الدراسية')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-door-open me-2 text-primary"></i>فصولي الدراسية</h1>
        <p class="page-subtitle">الفصول الدراسية المخصصة لك</p>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-door-open me-2"></i>قائمة الفصول</h6>
        <span class="badge bg-primary rounded-pill">{{ count($classrooms) }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>الفصل</th>
                    <th>المرحلة</th>
                    <th>عدد الطلاب</th>
                    <th>السعة</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                @php
                    $fillPct = min(100, ($classroom->children_count / max(1, $classroom->capacity)) * 100);
                    $fillColor = $fillPct >= 100 ? 'danger' : ($fillPct >= 75 ? 'warning' : 'success');
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;">
                                <i class="bi bi-door-open" style="font-size:0.9rem;"></i>
                            </div>
                            <strong>{{ $classroom->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $classroom->stage->name }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-fill" style="height:6px;">
                                <div class="progress-bar bg-{{ $fillColor }}" style="width:{{ $fillPct }}%"></div>
                            </div>
                            <span class="badge bg-{{ $fillColor }} rounded-pill">{{ $classroom->children_count }}</span>
                        </div>
                    </td>
                    <td>{{ $classroom->capacity }} طفل</td>
                    <td>
                        <span class="badge bg-{{ $classroom->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $classroom->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-door-closed"></i>
                            <h5>لا توجد فصول دراسية</h5>
                            <p>لم يتم تخصيص أي فصول لك بعد</p>
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
            @forelse($classrooms as $classroom)
            @php
                $fillPct = min(100, ($classroom->children_count / max(1, $classroom->capacity)) * 100);
                $fillColor = $fillPct >= 100 ? 'danger' : ($fillPct >= 75 ? 'warning' : 'success');
            @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-door-open"></i>
                </div>
                <div class="item-content">
                    <h6>{{ $classroom->name }}</h6>
                    <p>{{ $classroom->stage->name }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $fillColor }} rounded-pill">{{ $classroom->children_count }} / {{ $classroom->capacity }}</span>
                        <span class="badge bg-{{ $classroom->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $classroom->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-door-closed"></i>
                <h5>لا توجد فصول</h5>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
