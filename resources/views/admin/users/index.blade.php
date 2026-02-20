@extends('layouts.app')

@section('page-title', 'المستخدمون')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-person-gear me-2 text-primary"></i>إدارة المستخدمين</h1>
        <p class="page-subtitle">إدارة حسابات المديرين والمعلمين وأولياء الأمور</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>إضافة مستخدم
    </a>
</div>

<!-- Filters -->
<div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">بحث</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="الاسم أو البريد الإلكتروني..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">الدور</label>
                    <select name="role" class="form-select">
                        <option value="">جميع الأدوار</option>
                        <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>مدير</option>
                        <option value="Teacher" {{ request('role') === 'Teacher' ? 'selected' : '' }}>معلم</option>
                        <option value="Parent" {{ request('role') === 'Parent' ? 'selected' : '' }}>ولي أمر</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search me-1"></i>بحث
                        </button>
                        @if(request()->hasAny(['search','role']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
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
        <h6><i class="bi bi-people me-2"></i>قائمة المستخدمين</h6>
        <span class="badge bg-primary rounded-pill">{{ $users->total() }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الهاتف</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $roleColors = ['Admin'=>'danger','Teacher'=>'primary','Parent'=>'success'];
                    $roleLabels = ['Admin'=>'مدير','Teacher'=>'معلم','Parent'=>'ولي أمر'];
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                 class="rounded-circle" style="width:40px;height:40px;object-fit:cover;border:2px solid var(--glass-border)">
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }} rounded-pill">
                            {{ $roleLabels[$role->name] ?? $role->name }}
                        </span>
                        @endforeach
                    </td>
                    <td>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn btn-sm btn-outline-warning" title="تعديل">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h5>لا يوجد مستخدمون</h5>
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
            @forelse($users as $user)
            @php
                $roleColors = ['Admin'=>'danger','Teacher'=>'primary','Parent'=>'success'];
                $roleLabels = ['Admin'=>'مدير','Teacher'=>'معلم','Parent'=>'ولي أمر'];
            @endphp
            <div class="list-item">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="item-avatar">
                <div class="item-content">
                    <h6>{{ $user->name }}</h6>
                    <p>{{ $user->email }}</p>
                    <div class="item-meta">
                        @foreach($user->roles as $role)
                        <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }} rounded-pill small">
                            {{ $roleLabels[$role->name] ?? $role->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5>لا يوجد مستخدمون</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($users->hasPages())
    <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection
