@extends('layouts.app')

@section('page-title', 'الملف الشخصي')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <h1 class="page-title"><i class="bi bi-person-circle me-2 text-primary"></i>الملف الشخصي</h1>
    <p class="page-subtitle">إدارة معلوماتك الشخصية وإعدادات الحساب</p>
</div>

<div class="row g-4">
    <!-- Profile Info & Password -->
    <div class="col-lg-8">

        <!-- Profile Information -->
        <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
            <div class="card-header">
                <h6><i class="bi bi-person me-2 text-primary"></i>معلومات الملف الشخصي</h6>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    <!-- Avatar -->
                    <div class="text-center mb-4">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                             class="rounded-circle mb-3"
                             style="width:100px;height:100px;object-fit:cover;border:3px solid var(--glass-border);">
                        <div>
                            <label for="avatar" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-camera me-1"></i>تغيير الصورة
                            </label>
                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($user->email_verified_at === null)
                            <small class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>البريد الإلكتروني غير مؤكد</small>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">رقم الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}">
                            </div>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="50">
            <div class="card-header">
                <h6><i class="bi bi-lock me-2 text-primary"></i>تغيير كلمة المرور</h6>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">كلمة المرور الحالية <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="current_password"
                                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                            </div>
                            @error('current_password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password"
                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                            </div>
                            @error('password', 'updatePassword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="password_confirmation"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>تحديث كلمة المرور
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Information -->
    <div class="col-lg-4">
        <div class="glass-card glass-card-no-hover" data-aos="fade-left">
            <div class="card-header">
                <h6><i class="bi bi-info-circle me-2 text-primary"></i>معلومات الحساب</h6>
            </div>
            <div class="card-body">
                <!-- Avatar large -->
                <div class="text-center mb-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="rounded-circle"
                         style="width:80px;height:80px;object-fit:cover;border:3px solid var(--glass-border);">
                    <h6 class="mt-2 mb-0 fw-600">{{ $user->name }}</h6>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>

                <hr style="border-color:var(--glass-border);">

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">الدور</span>
                        <div>
                            @php $roleColors = ['Admin'=>'danger','Teacher'=>'primary','Parent'=>'success']; @endphp
                            @foreach($user->roles as $role)
                            <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }} rounded-pill">
                                {{ ['Admin'=>'مدير','Teacher'=>'معلم','Parent'=>'ولي أمر'][$role->name] ?? $role->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">تاريخ التسجيل</span>
                        <span class="fw-600 small">{{ $user->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">الحالة</span>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">البريد الإلكتروني</span>
                        <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }} rounded-pill">
                            {{ $user->email_verified_at ? 'مؤكد' : 'غير مؤكد' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
