@extends('layouts.app')

@section('page-title', 'رفع صور جديدة')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('teacher.photos.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-cloud-upload me-2 text-primary"></i>رفع صور جديدة</h1>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('teacher.photos.store') }}" method="POST" enctype="multipart/form-data">
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
                            <small class="text-muted">اختر الفصل أولاً (الأطفال الذين لديهم موافقة التصوير فقط)</small>
                            @endif
                            @error('child_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card-header">
                    <h6><i class="bi bi-info-circle me-2 text-primary"></i>تفاصيل الصور</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الصورة <span class="text-danger">*</span></label>
                            <input type="date" name="photo_date" id="photo_date"
                                   class="form-control @error('photo_date') is-invalid @enderror"
                                   value="{{ old('photo_date', date('Y-m-d')) }}" required>
                            @error('photo_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">النشاط</label>
                            <input type="text" name="activity" id="activity"
                                   class="form-control @error('activity') is-invalid @enderror"
                                   value="{{ old('activity') }}"
                                   placeholder="مثال: رسم، موسيقى، ألعاب...">
                            @error('activity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" id="description" rows="2"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="وصف الصور...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">الصور <span class="text-danger">*</span></label>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                   class="form-control @error('photos') is-invalid @enderror"
                                   required {{ $children->isEmpty() ? 'disabled' : '' }}>
                            <small class="text-muted">يمكنك اختيار عدة صور في نفس الوقت</small>
                            @error('photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @error('photos.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <!-- Preview Area -->
                        <div class="col-12">
                            <div id="preview-area" class="row g-2" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="100" style="border-right:4px solid var(--info);">
                <div class="card-body">
                    <p class="mb-0 text-muted">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        <strong>ملاحظة:</strong> سيتم عرض الأطفال الذين لديهم موافقة ولي الأمر على التصوير فقط
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-between" data-aos="fade-up" data-aos-delay="150">
                <a href="{{ route('teacher.photos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary" {{ $children->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-cloud-upload me-1"></i>رفع الصور
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('photos').addEventListener('change', function(e) {
    const previewArea = document.getElementById('preview-area');
    previewArea.innerHTML = '';
    previewArea.style.display = 'flex';
    previewArea.style.flexWrap = 'wrap';
    previewArea.style.gap = '8px';

    Array.from(e.target.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.cssText = 'width:80px;height:80px;border-radius:8px;overflow:hidden;border:2px solid var(--glass-border);';
                div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
                previewArea.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
@endsection
