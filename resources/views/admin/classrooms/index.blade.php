@extends('layouts.app')

@section('page-title', 'الفصول الدراسية')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-building-fill me-2 text-primary"></i>الفصول الدراسية</h1>
        <p class="page-subtitle">إدارة فصول الحضانة وسعتها</p>
    </div>
    <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>إضافة فصل
    </a>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-building me-2"></i>قائمة الفصول</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>الفصل</th>
                    <th>المرحلة</th>
                    <th>السعة</th>
                    <th>الأطفال المسجلون</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classrooms as $classroom)
                @php $isFull = $classroom->children_count >= $classroom->capacity; @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;">
                                <i class="bi bi-building" style="font-size:0.9rem;"></i>
                            </div>
                            <strong>{{ $classroom->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $classroom->stage->name }}</td>
                    <td>{{ $classroom->capacity }} طفل</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;max-width:80px;">
                                <div class="progress-bar bg-{{ $isFull ? 'danger' : 'success' }}"
                                     style="width:{{ min(100, ($classroom->children_count / max(1,$classroom->capacity)) * 100) }}%"></div>
                            </div>
                            <span class="badge bg-{{ $isFull ? 'danger' : 'success' }} rounded-pill">
                                {{ $classroom->children_count }}/{{ $classroom->capacity }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $classroom->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $classroom->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            @if(method_exists('\Illuminate\Support\Facades\Route', 'has') && \Illuminate\Support\Facades\Route::has('admin.classrooms.show'))
                            <a href="{{ route('admin.classrooms.show', $classroom) }}"
                               class="btn btn-sm btn-outline-info" title="عرض">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif
                            <a href="{{ route('admin.classrooms.edit', $classroom) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.classrooms.destroy', $classroom) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الفصل؟')">
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
                            <i class="bi bi-building"></i>
                            <h5>لا توجد فصول دراسية</h5>
                            <p>ابدأ بإضافة فصول دراسية للحضانة</p>
                            <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>إضافة فصل
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($classrooms->hasPages())
    <div class="card-footer">{{ $classrooms->links() }}</div>
    @endif
</div>
@endsection
