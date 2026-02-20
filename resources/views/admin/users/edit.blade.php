@extends('layouts.app')

@section('page-title', 'تعديل المستخدم')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-pencil-fill me-2 text-warning"></i>تعديل: {{ $user->name }}</h1>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            @php $roleLabels = ['Admin'=>'مدير','Teacher'=>'معلم','Parent'=>'ولي أمر']; @endphp

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2 text-primary"></i>البيانات الأساسية</h6>
                </div>
                <div class="card-body">
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
                        <div class="col-md-6">
                            <label class="form-label">كلمة المرور الجديدة <small class="text-muted">(اختياري)</small></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="اتركه فارغاً للإبقاء على كلمة المرور الحالية">
                            </div>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الدور <span class="text-danger">*</span></label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">اختر الدور...</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                    {{ $roleLabels[$role->name] ?? $role->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active"
                                       class="form-check-input" value="1"
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label fw-600">حساب نشط</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->hasRole('Teacher'))
            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card-header">
                    <h6><i class="bi bi-person-workspace me-2 text-primary"></i>تخصيصات المعلم</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-600">الفصول المخصصة</label>
                            <div class="glass-surface p-3 rounded">
                                @foreach($classrooms as $classroom)
                                <div class="form-check">
                                    <input type="checkbox" name="classrooms[]" value="{{ $classroom->id }}"
                                           class="form-check-input" id="classroom_{{ $classroom->id }}"
                                           {{ in_array($classroom->id, old('classrooms', $user->teacherClassrooms->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="classroom_{{ $classroom->id }}">
                                        {{ $classroom->name }} <small class="text-muted">({{ $classroom->stage->name }})</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">المواد المخصصة</label>
                            <div class="glass-surface p-3 rounded">
                                @foreach($subjects as $subject)
                                <div class="form-check">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                           class="form-check-input" id="subject_{{ $subject->id }}"
                                           {{ in_array($subject->id, old('subjects', $user->teacherSubjects->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                                        {{ $subject->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
