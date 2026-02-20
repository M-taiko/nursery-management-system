@extends('layouts.app')

@section('page-title', 'تعديل المرحلة')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.stages.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-pencil-fill me-2 text-warning"></i>تعديل: {{ $stage->name }}</h1>
            <p class="page-subtitle">تعديل بيانات المرحلة الدراسية</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('admin.stages.update', $stage) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="glass-card glass-card-no-hover" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-pencil me-2 text-warning"></i>بيانات المرحلة</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">اسم المرحلة <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $stage->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">من عمر (سنة) <span class="text-danger">*</span></label>
                            <input type="number" name="age_from"
                                   class="form-control @error('age_from') is-invalid @enderror"
                                   value="{{ old('age_from', $stage->age_from) }}" min="0" max="10" required>
                            @error('age_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">إلى عمر (سنة) <span class="text-danger">*</span></label>
                            <input type="number" name="age_to"
                                   class="form-control @error('age_to') is-invalid @enderror"
                                   value="{{ old('age_to', $stage->age_to) }}" min="0" max="10" required>
                            @error('age_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">الرسوم الشهرية <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="monthly_fee" step="0.01"
                                       class="form-control @error('monthly_fee') is-invalid @enderror"
                                       value="{{ old('monthly_fee', $stage->monthly_fee) }}" min="0" required>
                                <span class="input-group-text">ج.م</span>
                            </div>
                            @error('monthly_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $stage->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if(isset($subjects) && $subjects->count() > 0)
                        <div class="col-12">
                            <label class="form-label">المواد الدراسية</label>
                            <div class="row g-2 p-3 glass-surface rounded">
                                @foreach($subjects as $subject)
                                <div class="col-md-4 col-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                               class="form-check-input" id="subject_{{ $subject->id }}"
                                               {{ in_array($subject->id, old('subjects', $stage->subjects->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                                            {{ $subject->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active"
                                       class="form-check-input" value="1"
                                       {{ old('is_active', $stage->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label fw-600">مرحلة نشطة</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.stages.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
