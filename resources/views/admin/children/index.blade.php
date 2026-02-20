@extends('layouts.app')

@section('page-title', 'إدارة الأطفال')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-people-fill me-2 text-primary"></i>إدارة الأطفال</h1>
        <p class="page-subtitle">عرض وإدارة جميع الأطفال المسجلين في الحضانة</p>
    </div>
    <a href="{{ route('admin.children.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>إضافة طفل
    </a>
</div>

<!-- Filters Card -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.children.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3 col-6">
                    <label class="form-label">بحث بالاسم</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="اسم الطفل..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">المرحلة</label>
                    <select name="stage_id" class="form-select">
                        <option value="">جميع المراحل</option>
                        @foreach($stages as $stage)
                        <option value="{{ $stage->id }}" {{ request('stage_id') == $stage->id ? 'selected' : '' }}>
                            {{ $stage->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-6">
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
                <div class="col-md-2 col-6">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>متخرج</option>
                    </select>
                </div>
                <div class="col-md-1 col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request()->hasAny(['search','stage_id','classroom_id','status']))
                        <a href="{{ route('admin.children.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Children Table -->
<div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header">
        <h6><i class="bi bi-people me-2"></i>قائمة الأطفال</h6>
        <span class="badge bg-primary rounded-pill">{{ $children->total() }} طفل</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>الطفل</th>
                    <th>العمر</th>
                    <th>المرحلة / الفصل</th>
                    <th>ولي الأمر</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($children as $child)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                                 class="rounded-circle" style="width:42px;height:42px;object-fit:cover;border:2px solid var(--glass-border)">
                            <div>
                                <div class="fw-600 text-primary-emphasis">{{ $child->name }}</div>
                                @if($child->national_id)
                                <small class="text-muted">{{ $child->national_id }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-500">{{ $child->age }}</span>
                        <small class="text-muted"> سنة</small>
                    </td>
                    <td>
                        <div>{{ $child->stage->name }}</div>
                        <small class="text-muted">{{ $child->classroom->name }}</small>
                    </td>
                    <td>
                        <div>{{ $child->parent->name }}</div>
                        @if($child->parent->phone)
                        <small class="text-muted"><i class="bi bi-phone me-1"></i>{{ $child->parent->phone }}</small>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = ['active'=>'success','inactive'=>'secondary','graduated'=>'primary'];
                            $statusLabels = ['active'=>'نشط','inactive'=>'غير نشط','graduated'=>'متخرج'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$child->status] ?? 'secondary' }} rounded-pill">
                            {{ $statusLabels[$child->status] ?? $child->status }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.children.show', $child) }}"
                               class="btn btn-sm btn-outline-info" title="عرض">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.children.edit', $child) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.children.destroy', $child) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الطفل؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h5>لا يوجد أطفال</h5>
                            <p>لم يتم إضافة أي أطفال بعد أو لا توجد نتائج للبحث</p>
                            <a href="{{ route('admin.children.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>إضافة أول طفل
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card List -->
    <div class="d-md-none p-3">
        <div class="card-list">
            @forelse($children as $child)
            <div class="list-item">
                <img src="{{ $child->photo_url }}" alt="{{ $child->name }}" class="item-avatar">
                <div class="item-content">
                    <h6>{{ $child->name }}</h6>
                    <p>{{ $child->stage->name }} &bull; {{ $child->classroom->name }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $statusColors[$child->status] ?? 'secondary' }} rounded-pill">
                            {{ $statusLabels[$child->status] ?? $child->status }}
                        </span>
                        <small class="text-muted">{{ $child->age }} سنة</small>
                    </div>
                </div>
                <div class="item-actions">
                    <a href="{{ route('admin.children.show', $child) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5>لا يوجد أطفال</h5>
                <p>ابدأ بإضافة الأطفال إلى النظام</p>
            </div>
            @endforelse
        </div>
    </div>

    @if($children->hasPages())
    <div class="card-footer">
        {{ $children->links() }}
    </div>
    @endif
</div>
@endsection
