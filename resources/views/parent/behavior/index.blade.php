@extends('layouts.app')

@section('page-title', 'سلوك ' . $child->name)

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('parent.children.show', $child) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-emoji-smile me-2 text-primary"></i>سجل سلوك {{ $child->name }}</h1>
            <p class="page-subtitle">سجل الملاحظات السلوكية</p>
        </div>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-journal-text me-2"></i>سجل السلوك</h6>
        <span class="badge bg-primary rounded-pill">{{ $records->total() }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>النوع</th>
                    <th>الفئة</th>
                    <th>الوصف</th>
                    <th>الإجراء المتخذ</th>
                    <th>المعلم</th>
                    <th>تم إخطار ولي الأمر</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                @php
                    $typeColor = ['positive'=>'success','negative'=>'danger','neutral'=>'secondary'][$record->type] ?? 'secondary';
                    $typeIcon  = ['positive'=>'bi-emoji-smile','negative'=>'bi-emoji-frown','neutral'=>'bi-emoji-neutral'][$record->type] ?? 'bi-emoji-neutral';
                @endphp
                <tr>
                    <td>
                        <span class="badge bg-{{ $typeColor }} rounded-pill">
                            <i class="bi {{ $typeIcon }} me-1"></i>{{ $record->type_label }}
                        </span>
                    </td>
                    <td>{{ $record->category ?? '-' }}</td>
                    <td>{{ Str::limit($record->description, 60) }}</td>
                    <td>
                        @if($record->action_taken)
                        <span class="text-muted" style="font-size:0.85rem;">{{ Str::limit($record->action_taken, 50) }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $record->teacher->name ?? '-' }}</td>
                    <td>
                        @if($record->parent_notified)
                        <span class="badge bg-success rounded-pill"><i class="bi bi-check"></i> نعم</span>
                        @else
                        <span class="badge bg-secondary rounded-pill">لا</span>
                        @endif
                    </td>
                    <td>{{ $record->record_date->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-emoji-smile"></i>
                            <h5>لا توجد سجلات سلوك</h5>
                            <p>لم يتم تسجيل أي ملاحظات سلوكية بعد</p>
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
            @forelse($records as $record)
            @php
                $typeColor = ['positive'=>'success','negative'=>'danger','neutral'=>'secondary'][$record->type] ?? 'secondary';
                $typeIcon  = ['positive'=>'bi-emoji-smile','negative'=>'bi-emoji-frown','neutral'=>'bi-emoji-neutral'][$record->type] ?? 'bi-emoji-neutral';
            @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:var(--bg-surface);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi {{ $typeIcon }} text-{{ $typeColor }}" style="font-size:1.3rem;"></i>
                </div>
                <div class="item-content">
                    <h6>
                        <span class="badge bg-{{ $typeColor }} rounded-pill">{{ $record->type_label }}</span>
                        @if($record->category) <small class="text-muted ms-1">{{ $record->category }}</small> @endif
                    </h6>
                    <p>{{ Str::limit($record->description, 70) }}</p>
                    <div class="item-meta">
                        <small class="text-muted">{{ $record->teacher->name ?? '-' }}</small>
                        <small class="text-muted">{{ $record->record_date->format('Y-m-d') }}</small>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-emoji-smile"></i>
                <h5>لا توجد سجلات سلوك</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($records->hasPages())
    <div class="card-footer">{{ $records->links() }}</div>
    @endif
</div>
@endsection
