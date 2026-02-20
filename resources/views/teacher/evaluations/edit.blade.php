@extends('layouts.app')

@section('page-title', 'تعديل التقييم')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('teacher.evaluations.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-pencil-fill me-2 text-warning"></i>تعديل تقييم: {{ $evaluation->child->name }}</h1>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('teacher.evaluations.update', $evaluation) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2 text-primary"></i>بيانات التقييم</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الطفل</label>
                            <input type="text" class="form-control" value="{{ $evaluation->child->name }}" disabled>
                            <input type="hidden" name="child_id" value="{{ $evaluation->child_id }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">المادة <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id"
                                    class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">اختر المادة...</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $evaluation->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">تاريخ التقييم <span class="text-danger">*</span></label>
                            <input type="date" name="evaluation_date" id="evaluation_date"
                                   class="form-control @error('evaluation_date') is-invalid @enderror"
                                   value="{{ old('evaluation_date', $evaluation->evaluation_date->format('Y-m-d')) }}" required>
                            @error('evaluation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card-header">
                    <h6><i class="bi bi-graph-up me-2 text-primary"></i>تفاصيل التقييم</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">مستوى الفهم <span class="text-danger">*</span></label>
                            <select name="understanding_level" id="understanding_level"
                                    class="form-select @error('understanding_level') is-invalid @enderror" required>
                                <option value="">اختر...</option>
                                <option value="excellent"         {{ old('understanding_level', $evaluation->understanding_level) === 'excellent'         ? 'selected' : '' }}>ممتاز</option>
                                <option value="very_good"         {{ old('understanding_level', $evaluation->understanding_level) === 'very_good'         ? 'selected' : '' }}>جيد جداً</option>
                                <option value="good"              {{ old('understanding_level', $evaluation->understanding_level) === 'good'              ? 'selected' : '' }}>جيد</option>
                                <option value="average"           {{ old('understanding_level', $evaluation->understanding_level) === 'average'           ? 'selected' : '' }}>مقبول</option>
                                <option value="needs_improvement" {{ old('understanding_level', $evaluation->understanding_level) === 'needs_improvement' ? 'selected' : '' }}>يحتاج تحسين</option>
                            </select>
                            @error('understanding_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">نسبة الاستيعاب (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="comprehension_percentage" id="comprehension_percentage"
                                       class="form-control @error('comprehension_percentage') is-invalid @enderror"
                                       min="0" max="100"
                                       value="{{ old('comprehension_percentage', $evaluation->comprehension_percentage) }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('comprehension_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">السلوك <span class="text-danger">*</span></label>
                            <select name="behavior" id="behavior"
                                    class="form-select @error('behavior') is-invalid @enderror" required>
                                <option value="">اختر...</option>
                                <option value="excellent"         {{ old('behavior', $evaluation->behavior) === 'excellent'         ? 'selected' : '' }}>ممتاز</option>
                                <option value="very_good"         {{ old('behavior', $evaluation->behavior) === 'very_good'         ? 'selected' : '' }}>جيد جداً</option>
                                <option value="good"              {{ old('behavior', $evaluation->behavior) === 'good'              ? 'selected' : '' }}>جيد</option>
                                <option value="average"           {{ old('behavior', $evaluation->behavior) === 'average'           ? 'selected' : '' }}>مقبول</option>
                                <option value="needs_improvement" {{ old('behavior', $evaluation->behavior) === 'needs_improvement' ? 'selected' : '' }}>يحتاج تحسين</option>
                            </select>
                            @error('behavior')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="أي ملاحظات إضافية...">{{ old('notes', $evaluation->notes) }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('teacher.evaluations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>تحديث التقييم
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
