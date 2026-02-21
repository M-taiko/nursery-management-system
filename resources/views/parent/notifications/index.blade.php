@extends('layouts.app')

@section('page-title', 'الإشعارات')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-bell me-2 text-primary"></i>الإشعارات</h1>
        <p class="page-subtitle">جميع إشعاراتك</p>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-bell me-2"></i>قائمة الإشعارات</h6>
        <span class="badge bg-primary rounded-pill">{{ $notifications->total() }}</span>
    </div>

    <div class="card-body p-0">
        @forelse($notifications as $notification)
        <div class="list-item p-3 {{ $notification->read_at ? '' : 'border-start border-primary border-3' }}"
             style="border-bottom:1px solid var(--glass-border);{{ $notification->read_at ? '' : 'background:rgba(var(--primary-rgb,78,115,223),0.05);' }}">
            <div class="d-flex align-items-start gap-3">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-bell"></i>
                </div>
                <div class="flex-fill">
                    <p class="mb-1 {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}">
                        {{ $notification->data['message'] ?? $notification->data['title'] ?? 'إشعار جديد' }}
                    </p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                <form method="POST" action="{{ route('parent.notifications.read', $notification->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary" title="تحديد كمقروء">
                        <i class="bi bi-check2"></i>
                    </button>
                </form>
                @else
                <span class="badge bg-secondary rounded-pill" style="font-size:0.7rem;">مقروء</span>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state py-5">
            <i class="bi bi-bell-slash" style="font-size:3rem;color:var(--text-muted);"></i>
            <h5 class="mt-3">لا توجد إشعارات</h5>
            <p class="text-muted">ليس لديك أي إشعارات حتى الآن</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="card-footer">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
