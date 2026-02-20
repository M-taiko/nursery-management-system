@extends('layouts.app')

@section('page-title', 'تسجيل سلوك جديد')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('teacher.behavior.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-emoji-smile me-2 text-primary"></i>تسجيل سلوك جديد</h1>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('teacher.behavior.store') }}" method="POST">
            @csrf

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2 text-primary"></i>اختيار الطالب</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الفصل <span class="text-danger">*</span></label>
                            <select name="classroom_id" id="classroom_id"
                                    class="form-select @error('classroom_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">اختر الفصل...</option>
                                @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} — {{ $classroom->stage->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('classroom_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">الطفل <span class="text-danger">*</span></label>
                            <select name="child_id" id="child_id"
                                    class="form-select @error('child_id') is-invalid @enderror" required
                                    {{ $children->isEmpty() ? 'disabled' : '' }}>
                                <option value="">اختر الطفل...</option>
                                @foreach($children as $child)
                                <option value="{{ $child->id }}" {{ old('child_id') == $child->id ? 'selected' : '' }}>
                                    {{ $child->name }}
                                </option>
                                @endforeach
                            </select>
                            @if($children->isEmpty())
                            <small class="text-muted">اختر الفصل أولاً لعرض الأطفال</small>
                            @endif
                            @error('child_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="record_date" id="record_date"
                                   class="form-control @error('record_date') is-invalid @enderror"
                                   value="{{ old('record_date', date('Y-m-d')) }}" required>
                            @error('record_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">نوع السلوك <span class="text-danger">*</span></label>
                            <select name="type" id="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">اختر...</option>
                                <option value="positive" {{ old('type') === 'positive' ? 'selected' : '' }}>إيجابي</option>
                                <option value="negative" {{ old('type') === 'negative' ? 'selected' : '' }}>سلبي</option>
                                <option value="neutral"  {{ old('type') === 'neutral'  ? 'selected' : '' }}>محايد</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card-header">
                    <h6><i class="bi bi-card-text me-2 text-primary"></i>تفاصيل السلوك</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">الوصف <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="اكتب وصف السلوك بالتفصيل..." required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">الإجراء المتخذ</label>
                            <textarea name="action_taken" id="action_taken" rows="2"
                                      class="form-control @error('action_taken') is-invalid @enderror"
                                      placeholder="الإجراء الذي تم اتخاذه...">{{ old('action_taken') }}</textarea>
                            @error('action_taken')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('teacher.behavior.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary" {{ $children->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-check-circle me-1"></i>حفظ السجل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
