@extends('layouts.app')

@section('page-title', 'المراحل الدراسية')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-layers-fill me-2 text-primary"></i>المراحل الدراسية</h1>
        <p class="page-subtitle">إدارة المراحل العمرية في الحضانة</p>
    </div>
    <a href="{{ route('admin.stages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>إضافة مرحلة
    </a>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-layers me-2"></i>قائمة المراحل</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>المرحلة</th>
                    <th>الفئة العمرية</th>
                    <th>الرسوم الشهرية</th>
                    <th class="text-center">الفصول</th>
                    <th class="text-center">الأطفال</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stages as $stage)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;">
                                <i class="bi bi-mortarboard" style="font-size:0.9rem;"></i>
                            </div>
                            <strong>{{ $stage->name }}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-info rounded-pill">{{ $stage->age_from }} - {{ $stage->age_to }} سنة</span>
                    </td>
                    <td class="fw-600 text-success">{{ number_format($stage->monthly_fee, 2) }} ج.م</td>
                    <td class="text-center">
                        <span class="badge bg-secondary rounded-pill">{{ $stage->classrooms_count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary rounded-pill">{{ $stage->children_count }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $stage->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $stage->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.stages.edit', $stage) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.stages.destroy', $stage) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المرحلة؟')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-layers"></i>
                            <h5>لا توجد مراحل دراسية</h5>
                            <p>ابدأ بإضافة مراحل دراسية للحضانة</p>
                            <a href="{{ route('admin.stages.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>إضافة مرحلة
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stages->hasPages())
    <div class="card-footer">{{ $stages->links() }}</div>
    @endif
</div>
@endsection
