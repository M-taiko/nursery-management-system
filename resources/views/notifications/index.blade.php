@extends('layouts.app')

@section('page-title', 'الإشعارات')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-bell me-2 text-primary"></i>الإشعارات</h1>
        <p class="page-subtitle">جميع الإشعارات والتنبيهات</p>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form action="{{ route('notifications.readAll') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-check-all me-1"></i>تعليم الكل كمقروء
        </button>
    </form>
    @endif
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-bell me-2"></i>الإشعارات</h6>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() }} جديد</span>
        @endif
    </div>

    @php
    $notifIcons = [
        'evaluation' => ['icon' => 'bi-clipboard-check', 'color' => 'primary'],
        'photo'      => ['icon' => 'bi-camera',           'color' => 'success'],
        'payment'    => ['icon' => 'bi-cash',             'color' => 'success'],
        'invoice'    => ['icon' => 'bi-receipt',          'color' => 'warning'],
    ];
    @endphp

    <div class="card-body p-0">
        @forelse($notifications as $notification)
        @php
            $type   = $notification->data['type'] ?? 'default';
            $ni     = $notifIcons[$type] ?? ['icon' => 'bi-bell', 'color' => 'info'];
            $unread = is_null($notification->read_at);
        @endphp
        <div class="d-flex align-items-start gap-3 p-3"
             style="{{ $unread ? 'border-right:3px solid var(--primary);background:rgba(99,102,241,0.05);' : '' }}border-bottom:1px solid var(--glass-border);">
            <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:44px;height:44px;border-radius:12px;border:1px solid var(--glass-border);background:var(--glass-backdrop);">
                <i class="bi {{ $ni['icon'] }} text-{{ $ni['color'] }}" style="font-size:1.2rem;"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-600">{{ $notification->data['title'] ?? 'إشعار جديد' }}</h6>
                <p class="mb-1 text-muted small">{{ $notification->data['message'] ?? '' }}</p>
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                </small>
            </div>
            @if($unread)
            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary" title="تعليم كمقروء">
                    <i class="bi bi-check"></i>
                </button>
            </form>
            @else
            <span class="badge bg-secondary rounded-pill flex-shrink-0" style="font-size:0.7rem;">مقروء</span>
            @endif
        </div>
        @empty
        <div class="empty-state py-5">
            <i class="bi bi-bell-slash"></i>
            <h5>لا توجد إشعارات</h5>
            <p>ليس لديك إشعارات حتى الآن</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="card-footer">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
