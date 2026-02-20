@extends('layouts.app')

@section('page-title', 'تعديل الفصل')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.classrooms.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-pencil-fill me-2 text-warning"></i>تعديل: {{ $classroom->name }}</h1>
            <p class="page-subtitle">تعديل بيانات الفصل الدراسي</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <form action="{{ route('admin.classrooms.update', $classroom) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="glass-card glass-card-no-hover" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-pencil me-2 text-warning"></i>بيانات الفصل</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">اسم الفصل <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $classroom->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">المرحلة الدراسية <span class="text-danger">*</span></label>
                            <select name="stage_id" class="form-select @error('stage_id') is-invalid @enderror" required>
                                <option value="">اختر المرحلة...</option>
                                @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id', $classroom->stage_id) == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('stage_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">السعة الاستيعابية <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="capacity"
                                       class="form-control @error('capacity') is-invalid @enderror"
                                       value="{{ old('capacity', $classroom->capacity) }}" min="1" max="50" required>
                                <span class="input-group-text">طفل</span>
                            </div>
                            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active"
                                       class="form-check-input" value="1"
                                       {{ old('is_active', $classroom->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label fw-600">فصل نشط</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">
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
