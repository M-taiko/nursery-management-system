@extends('layouts.app')

@section('page-title', 'إضافة طفل جديد')

@section('content')
<div class="page-header mb-4" data-aos="fade-down">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.children.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
        </a>
        <div>
            <h1 class="page-title"><i class="bi bi-person-plus-fill me-2 text-primary"></i>إضافة طفل جديد</h1>
            <p class="page-subtitle">أدخل بيانات الطفل لتسجيله في النظام</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.children.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Right column: Main info -->
        <div class="col-lg-8">

            <!-- Basic Info -->
            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="50">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2 text-primary"></i>المعلومات الأساسية</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="أدخل الاسم الكامل" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الاسم بالعربية</label>
                            <input type="text" name="name_ar"
                                   class="form-control @error('name_ar') is-invalid @enderror"
                                   value="{{ old('name_ar') }}" placeholder="الاسم بالعربية">
                            @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الميلاد <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date"
                                   class="form-control @error('birth_date') is-invalid @enderror"
                                   value="{{ old('birth_date') }}" required>
                            @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الجنس <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">اختر الجنس...</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">رقم الهوية الوطنية</label>
                            <input type="text" name="national_id"
                                   class="form-control @error('national_id') is-invalid @enderror"
                                   value="{{ old('national_id') }}" placeholder="رقم الهوية">
                            @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">فصيلة الدم</label>
                            <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                                <option value="">غير محدد</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}" {{ old('blood_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                @endforeach
                            </select>
                            @error('blood_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-header">
                    <h6><i class="bi bi-book me-2 text-primary"></i>المعلومات الأكاديمية</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">المرحلة الدراسية <span class="text-danger">*</span></label>
                            <select name="stage_id" class="form-select @error('stage_id') is-invalid @enderror" required>
                                <option value="">اختر المرحلة...</option>
                                @foreach($stages as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->name }} ({{ $stage->age_from }}-{{ $stage->age_to }} سنة)
                                </option>
                                @endforeach
                            </select>
                            @error('stage_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                            <select name="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" required>
                                <option value="">اختر الفصل...</option>
                                @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                    {{ $classroom->name }} ({{ $classroom->stage->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('classroom_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ولي الأمر <span class="text-danger">*</span></label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror" required>
                                <option value="">اختر ولي الأمر...</option>
                                @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} ({{ $parent->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الالتحاق <span class="text-danger">*</span></label>
                            <input type="date" name="enrollment_date"
                                   class="form-control @error('enrollment_date') is-invalid @enderror"
                                   value="{{ old('enrollment_date', date('Y-m-d')) }}" required>
                            @error('enrollment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">حالة القيد <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="graduated" {{ old('status') === 'graduated' ? 'selected' : '' }}>متخرج</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Info -->
            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="150">
                <div class="card-header">
                    <h6><i class="bi bi-heart-pulse me-2 text-danger"></i>المعلومات الطبية</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">ملاحظات طبية</label>
                            <textarea name="medical_notes" rows="3"
                                      class="form-control @error('medical_notes') is-invalid @enderror"
                                      placeholder="أي حالات طبية خاصة أو أدوية...">{{ old('medical_notes') }}</textarea>
                            @error('medical_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">الحساسيات</label>
                            <textarea name="allergies" rows="2"
                                      class="form-control @error('allergies') is-invalid @enderror"
                                      placeholder="أنواع الحساسية إن وجدت...">{{ old('allergies') }}</textarea>
                            @error('allergies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">جهة الاتصال في الطوارئ</label>
                            <input type="text" name="emergency_contact"
                                   class="form-control @error('emergency_contact') is-invalid @enderror"
                                   value="{{ old('emergency_contact') }}" placeholder="اسم جهة الاتصال">
                            @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">هاتف الطوارئ</label>
                            <input type="text" name="emergency_phone"
                                   class="form-control @error('emergency_phone') is-invalid @enderror"
                                   value="{{ old('emergency_phone') }}" placeholder="رقم الهاتف">
                            @error('emergency_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Left column: Photo & Consent -->
        <div class="col-lg-4">
            <!-- Photo Upload -->
            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="80">
                <div class="card-header">
                    <h6><i class="bi bi-camera me-2 text-primary"></i>صورة الطفل</h6>
                </div>
                <div class="card-body text-center">
                    <div id="photoPreviewContainer" class="mb-3">
                        <img id="photoPreview" src="{{ asset('images/default-child.png') }}"
                             class="rounded-circle mb-2"
                             style="width:120px;height:120px;object-fit:cover;border:3px solid var(--glass-border);"
                             onerror="this.src='https://ui-avatars.com/api/?name=طفل&background=6366f1&color=fff&size=120'">
                    </div>
                    <label for="photo" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-upload me-1"></i>اختر صورة
                    </label>
                    <input type="file" name="photo" id="photo" accept="image/*"
                           class="d-none @error('photo') is-invalid @enderror"
                           onchange="previewPhoto(this)">
                    @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    <p class="text-muted small mt-2">PNG, JPG, WebP - حد أقصى 2MB</p>
                </div>
            </div>

            <!-- Consent -->
            <div class="glass-card glass-card-no-hover mb-4" data-aos="fade-up" data-aos-delay="120">
                <div class="card-header">
                    <h6><i class="bi bi-shield-check me-2 text-success"></i>الموافقات</h6>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" name="photo_consent" id="photo_consent"
                               class="form-check-input" value="1"
                               {{ old('photo_consent') ? 'checked' : '' }}>
                        <label for="photo_consent" class="form-check-label">
                            الموافقة على نشر صور الطفل في أنشطة الحضانة
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="160">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle me-1"></i>حفظ الطفل
                    </button>
                    <a href="{{ route('admin.children.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle me-1"></i>إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
