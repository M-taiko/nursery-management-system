@extends('layouts.app')

@section('page-title', 'طلابي')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-people me-2 text-primary"></i>قائمة طلابي</h1>
        <p class="page-subtitle">الأطفال في فصولك الدراسية</p>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-people me-2"></i>الطلاب</h6>
        <span class="badge bg-primary rounded-pill">{{ $children->total() }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>الطالب</th>
                    <th>العمر</th>
                    <th>المرحلة</th>
                    <th>الفصل</th>
                    <th>ولي الأمر</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($children as $child)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                                 class="rounded-circle" style="width:40px;height:40px;object-fit:cover;border:2px solid var(--glass-border);">
                            <strong>{{ $child->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $child->age }} سنوات</td>
                    <td>{{ $child->stage->name }}</td>
                    <td>{{ $child->classroom->name }}</td>
                    <td>
                        <div>{{ $child->parent->name }}</div>
                        <small class="text-muted">{{ $child->parent->phone }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                            {{ $child->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h5>لا يوجد طلاب</h5>
                            <p>لم يتم تعيين أي طلاب لفصولك</p>
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
            @forelse($children as $child)
            <div class="list-item">
                <img src="{{ $child->photo_url }}" alt="{{ $child->name }}" class="item-avatar">
                <div class="item-content">
                    <h6>{{ $child->name }}</h6>
                    <p>{{ $child->classroom->name }} &bull; {{ $child->age }} سنوات</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                            {{ $child->status_label }}
                        </span>
                        <small class="text-muted">{{ $child->parent->name }}</small>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5>لا يوجد طلاب</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($children->hasPages())
    <div class="card-footer">{{ $children->links() }}</div>
    @endif
</div>
@endsection
