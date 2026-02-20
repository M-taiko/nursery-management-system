@extends('layouts.app')

@section('page-title', 'سجل السلوك')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-emoji-smile me-2 text-primary"></i>سجل السلوك</h1>
        <p class="page-subtitle">تتبع سلوك الأطفال اليومي</p>
    </div>
    <a href="{{ route('teacher.behavior.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>تسجيل سلوك
    </a>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-list-check me-2"></i>السجلات</h6>
        <span class="badge bg-primary rounded-pill">{{ $records->total() }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>الطفل</th>
                    <th>النوع</th>
                    <th>الوصف</th>
                    <th>الإجراء المتخذ</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                @php
                    $typeMap = [
                        'positive' => ['label' => 'إيجابي', 'color' => 'success', 'icon' => 'emoji-smile'],
                        'negative' => ['label' => 'سلبي',   'color' => 'danger',  'icon' => 'emoji-frown'],
                        'neutral'  => ['label' => 'محايد',  'color' => 'secondary','icon' => 'emoji-neutral'],
                    ];
                    $t = $typeMap[$record->type] ?? ['label' => $record->type, 'color' => 'secondary', 'icon' => 'circle'];
                @endphp
                <tr>
                    <td>{{ $record->record_date->format('Y-m-d') }}</td>
                    <td><strong>{{ $record->child->name }}</strong></td>
                    <td><span class="badge bg-{{ $t['color'] }} rounded-pill">{{ $t['label'] }}</span></td>
                    <td class="text-muted">{{ Str::limit($record->description, 60) }}</td>
                    <td class="text-muted">{{ Str::limit($record->action_taken ?? '-', 50) }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('teacher.behavior.edit', $record) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-emoji-smile"></i>
                            <h5>لا توجد سجلات سلوك</h5>
                            <p>لم يتم تسجيل أي سلوك بعد</p>
                            <a href="{{ route('teacher.behavior.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>تسجيل سلوك
                            </a>
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
                $typeMap = [
                    'positive' => ['label' => 'إيجابي', 'color' => 'success'],
                    'negative' => ['label' => 'سلبي',   'color' => 'danger'],
                    'neutral'  => ['label' => 'محايد',  'color' => 'secondary'],
                ];
                $t = $typeMap[$record->type] ?? ['label' => $record->type, 'color' => 'secondary'];
            @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--{{ $t['color'] === 'success' ? 'primary' : ($t['color'] === 'danger' ? 'danger' : 'secondary') }}),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-emoji-{{ $record->type === 'positive' ? 'smile' : ($record->type === 'negative' ? 'frown' : 'neutral') }}"></i>
                </div>
                <div class="item-content">
                    <h6>{{ $record->child->name }}</h6>
                    <p>{{ $record->record_date->format('Y-m-d') }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $t['color'] }} rounded-pill">{{ $t['label'] }}</span>
                        <small class="text-muted">{{ Str::limit($record->description, 40) }}</small>
                    </div>
                </div>
                <a href="{{ route('teacher.behavior.edit', $record) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-emoji-smile"></i>
                <h5>لا توجد سجلات</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($records->hasPages())
    <div class="card-footer">{{ $records->links() }}</div>
    @endif
</div>
@endsection
