@extends('layouts.app')

@section('page-title', 'أطفالي')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-people me-2 text-primary"></i>أطفالي</h1>
        <p class="page-subtitle">معلومات أطفالك المسجلين في الحضانة</p>
    </div>
</div>

@if($children->isEmpty())
<div class="glass-card glass-card-no-hover text-center p-5" data-aos="fade-up">
    <div class="empty-state">
        <i class="bi bi-people" style="font-size:3rem;color:var(--text-muted);"></i>
        <h5 class="mt-3">لا يوجد أطفال مسجلين</h5>
        <p class="text-muted">لم يتم تسجيل أي أطفال تحت حسابك حتى الآن</p>
    </div>
</div>
@else
<div class="row g-4">
    @foreach($children as $child)
    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
        <div class="glass-card h-100">
            <div class="card-body text-center p-4">
                <img src="{{ $child->photo_url }}" alt="{{ $child->name }}"
                     class="rounded-circle mb-3"
                     style="width:90px;height:90px;object-fit:cover;border:3px solid var(--glass-border);">
                <h5 class="mb-1 fw-bold">{{ $child->name }}</h5>
                <p class="text-muted mb-2" style="font-size:0.9rem;">
                    {{ $child->stage->name }} &bull; {{ $child->classroom->name }}
                </p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-primary rounded-pill">{{ $child->age }}</span>
                    <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                        {{ $child->status_label }}
                    </span>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('parent.children.show', $child) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-lines-fill me-1"></i>الملف الشخصي
                    </a>
                    <div class="row g-1">
                        <div class="col-4">
                            <a href="{{ route('parent.evaluations.index', $child) }}"
                               class="btn btn-outline-info btn-sm w-100" title="التقييمات">
                                <i class="bi bi-clipboard-check"></i>
                            </a>
                        </div>
                        @if($child->photo_consent)
                        <div class="col-4">
                            <a href="{{ route('parent.photos.index', $child) }}"
                               class="btn btn-outline-success btn-sm w-100" title="الصور">
                                <i class="bi bi-images"></i>
                            </a>
                        </div>
                        @endif
                        <div class="col-4">
                            <a href="{{ route('parent.behavior.index', $child) }}"
                               class="btn btn-outline-warning btn-sm w-100" title="السلوك">
                                <i class="bi bi-emoji-smile"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
