@extends('layouts.app')

@section('page-title', 'تعديل سجل السلوك')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('teacher.behavior.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-pencil-fill me-2 text-warning"></i>تعديل سجل: {{ $behaviorRecord->child->name }}</h1>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('teacher.behavior.update', $behaviorRecord) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2 text-primary"></i>بيانات السجل</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">الطفل</label>
                            <input type="text" class="form-control" value="{{ $behaviorRecord->child->name }}" disabled>
                            <input type="hidden" name="child_id" value="{{ $behaviorRecord->child_id }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                            <input type="date" name="record_date" id="record_date"
                                   class="form-control @error('record_date') is-invalid @enderror"
                                   value="{{ old('record_date', $behaviorRecord->record_date->format('Y-m-d')) }}" required>
                            @error('record_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">نوع السلوك <span class="text-danger">*</span></label>
                            <select name="type" id="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">اختر...</option>
                                <option value="positive" {{ old('type', $behaviorRecord->type) === 'positive' ? 'selected' : '' }}>إيجابي</option>
                                <option value="negative" {{ old('type', $behaviorRecord->type) === 'negative' ? 'selected' : '' }}>سلبي</option>
                                <option value="neutral"  {{ old('type', $behaviorRecord->type) === 'neutral'  ? 'selected' : '' }}>محايد</option>
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
                                      placeholder="اكتب وصف السلوك بالتفصيل..." required>{{ old('description', $behaviorRecord->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">الإجراء المتخذ</label>
                            <textarea name="action_taken" id="action_taken" rows="2"
                                      class="form-control @error('action_taken') is-invalid @enderror"
                                      placeholder="الإجراء الذي تم اتخاذه...">{{ old('action_taken', $behaviorRecord->action_taken) }}</textarea>
                            @error('action_taken')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('teacher.behavior.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>تحديث السجل
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
