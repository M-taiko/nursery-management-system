@extends('layouts.app')

@section('page-title', 'المواد الدراسية')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-book-fill me-2 text-primary"></i>المواد الدراسية</h1>
        <p class="page-subtitle">إدارة المواد والأنشطة الدراسية</p>
    </div>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>إضافة مادة
    </a>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-book me-2"></i>قائمة المواد</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>المادة</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;">
                                <i class="bi bi-book" style="font-size:0.9rem;"></i>
                            </div>
                            <strong>{{ $subject->name }}</strong>
                        </div>
                    </td>
                    <td class="text-muted">{{ Str::limit($subject->description ?? '-', 60) }}</td>
                    <td>
                        <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $subject->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.subjects.edit', $subject) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.subjects.destroy', $subject) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
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
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="bi bi-book"></i>
                            <h5>لا توجد مواد دراسية</h5>
                            <p>ابدأ بإضافة المواد والأنشطة الدراسية</p>
                            <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>إضافة مادة
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subjects->hasPages())
    <div class="card-footer">{{ $subjects->links() }}</div>
    @endif
</div>
@endsection
