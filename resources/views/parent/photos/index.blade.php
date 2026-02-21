@extends('layouts.app')

@section('page-title', 'صور ' . $child->name)

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parent.children.show', $child) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-images me-2 text-primary"></i>صور {{ $child->name }}</h1>
            <p class="page-subtitle">معرض صور طفلك</p>
        </div>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-images me-2"></i>معرض الصور</h6>
        <span class="badge bg-primary rounded-pill">{{ $photos->total() }}</span>
    </div>
    <div class="card-body">
        @if($photos->isEmpty())
        <div class="empty-state py-5">
            <i class="bi bi-image" style="font-size:3rem;color:var(--text-muted);"></i>
            <h5 class="mt-3">لا توجد صور</h5>
            <p class="text-muted">لم يتم رفع أي صور لهذا الطفل بعد</p>
        </div>
        @else
        <div class="row g-3">
            @foreach($photos as $photo)
            <div class="col-6 col-md-4 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ ($loop->index % 8) * 50 }}">
                <div class="glass-card p-2 h-100">
                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" rel="noopener">
                        <img src="{{ asset('storage/' . ($photo->thumbnail_path ?? $photo->photo_path)) }}"
                             alt="{{ $photo->description ?? 'صورة' }}"
                             class="img-fluid rounded"
                             style="width:100%;height:140px;object-fit:cover;"
                             loading="lazy">
                    </a>
                    <div class="mt-2 text-center">
                        <small class="text-muted d-block">{{ $photo->photo_date->format('Y-m-d') }}</small>
                        @if($photo->description)
                        <small class="text-muted d-block" style="font-size:0.75rem;">{{ Str::limit($photo->description, 30) }}</small>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    @if($photos->hasPages())
    <div class="card-footer">{{ $photos->links() }}</div>
    @endif
</div>
@endsection
