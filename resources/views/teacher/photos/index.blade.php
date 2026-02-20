@extends('layouts.app')

@section('page-title', 'معرض الصور')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-images me-2 text-primary"></i>معرض الصور</h1>
        <p class="page-subtitle">صور الأطفال والأنشطة الدراسية</p>
    </div>
    <a href="{{ route('teacher.photos.create') }}" class="btn btn-primary">
        <i class="bi bi-cloud-upload me-1"></i>رفع صور
    </a>
</div>

<!-- Filters -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('teacher.photos.index') }}">
            <div class="row g-3 align-items-end">
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
                    <label class="form-label">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">النشاط</label>
                    <input type="text" name="activity" class="form-control"
                           placeholder="اسم النشاط..." value="{{ request('activity') }}">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>بحث
                        </button>
                        @if(request()->hasAny(['classroom_id','date','activity']))
                        <a href="{{ route('teacher.photos.index') }}" class="btn btn-outline-secondary">
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
        <h6><i class="bi bi-grid me-2"></i>الصور</h6>
        <span class="badge bg-primary rounded-pill">{{ $photos->total() }}</span>
    </div>

    <div class="card-body">
        <div class="row g-3">
            @forelse($photos as $photo)
            <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in">
                <div class="glass-card h-100" style="padding:0;overflow:hidden;">
                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" class="d-block">
                        <img src="{{ asset('storage/' . $photo->thumbnail_path) }}"
                             alt="{{ $photo->description }}"
                             style="width:100%;height:160px;object-fit:cover;border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
                    </a>
                    <div class="p-2">
                        <h6 class="mb-1 fw-600" style="font-size:0.85rem;">{{ $photo->child->name }}</h6>
                        <p class="text-muted mb-1" style="font-size:0.75rem;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $photo->photo_date->format('Y-m-d') }}
                        </p>
                        @if($photo->activity)
                        <p class="text-primary mb-1" style="font-size:0.75rem;">
                            <i class="bi bi-flag me-1"></i>{{ $photo->activity }}
                        </p>
                        @endif
                        <form action="{{ route('teacher.photos.destroy', $photo) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 mt-1">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-images"></i>
                    <h5>لا توجد صور</h5>
                    <p>لم يتم رفع أي صور بعد</p>
                    <a href="{{ route('teacher.photos.create') }}" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i>رفع صور
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @if($photos->hasPages())
    <div class="card-footer">{{ $photos->links() }}</div>
    @endif
</div>
@endsection
