@extends('layouts.app')

@section('page-title', 'خطط الرسوم')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between mb-4" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="bi bi-calculator me-2 text-primary"></i>خطط الرسوم</h1>
        <p class="page-subtitle">إدارة خطط وجداول الرسوم الدراسية</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.fees.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-receipt me-1"></i>الفواتير
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeePlanModal">
            <i class="bi bi-plus-circle me-1"></i>إضافة خطة
        </button>
    </div>
</div>

<div class="glass-card glass-card-no-hover" data-aos="fade-up">
    <div class="card-header">
        <h6><i class="bi bi-journal-text me-2"></i>قائمة الخطط</h6>
        <span class="badge bg-primary rounded-pill">{{ $feePlans->total() }}</span>
    </div>

    <!-- Desktop Table -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>الخطة</th>
                    <th>المرحلة</th>
                    <th>المبلغ</th>
                    <th>التكرار</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $freqMap = [
                        'monthly'     => ['label' => 'شهري',       'color' => 'info'],
                        'quarterly'   => ['label' => 'ربع سنوي',   'color' => 'primary'],
                        'semi_annual' => ['label' => 'نصف سنوي',   'color' => 'warning'],
                        'annual'      => ['label' => 'سنوي',       'color' => 'success'],
                    ];
                @endphp
                @forelse($feePlans as $plan)
                @php $f = $freqMap[$plan->frequency] ?? ['label' => $plan->frequency, 'color' => 'secondary']; @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                                <i class="bi bi-journal-check" style="font-size:0.9rem;"></i>
                            </div>
                            <strong>{{ $plan->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $plan->stage->name }}</td>
                    <td>
                        <span class="fw-600 text-primary">{{ number_format($plan->amount, 2) }}</span>
                        <small class="text-muted"> ج.م</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $f['color'] }} rounded-pill">{{ $f['label'] }}</span>
                    </td>
                    <td class="text-muted">{{ Str::limit($plan->description ?? '-', 50) }}</td>
                    <td>
                        <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $plan->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-journal-text"></i>
                            <h5>لا توجد خطط رسوم</h5>
                            <p>ابدأ بإضافة خطة رسوم جديدة</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeePlanModal">
                                <i class="bi bi-plus-circle me-1"></i>إضافة خطة
                            </button>
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
            @forelse($feePlans as $plan)
            @php $f = $freqMap[$plan->frequency] ?? ['label' => $plan->frequency, 'color' => 'secondary']; @endphp
            <div class="list-item">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="item-content">
                    <h6>{{ $plan->name }}</h6>
                    <p>{{ $plan->stage->name }}</p>
                    <div class="item-meta">
                        <span class="badge bg-{{ $f['color'] }} rounded-pill">{{ $f['label'] }}</span>
                        <small class="text-muted fw-600">{{ number_format($plan->amount, 2) }} ج.م</small>
                        <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }} rounded-pill">
                            {{ $plan->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="bi bi-journal-text"></i>
                <h5>لا توجد خطط رسوم</h5>
            </div>
            @endforelse
        </div>
    </div>

    @if($feePlans->hasPages())
    <div class="card-footer">{{ $feePlans->links() }}</div>
    @endif
</div>

<!-- Add Fee Plan Modal -->
<div class="modal fade" id="addFeePlanModal" tabindex="-1" aria-labelledby="addFeePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background:var(--glass-bg);backdrop-filter:var(--glass-blur);border:1px solid var(--glass-border);">
            <form action="{{ route('admin.fee-plans.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="border-color:var(--glass-border);">
                    <h5 class="modal-title" id="addFeePlanModalLabel">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>إضافة خطة رسوم جديدة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">اسم الخطة <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required
                                   placeholder="مثال: رسوم شهرية - الروضة">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">المرحلة <span class="text-danger">*</span></label>
                            <select name="stage_id"
                                    class="form-select @error('stage_id') is-invalid @enderror" required>
                                <option value="">اختر المرحلة...</option>
                                @foreach(\App\Models\Stage::all() as $stage)
                                <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>
                                    {{ $stage->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('stage_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">المبلغ <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="amount" step="0.01"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" required placeholder="0.00">
                                <span class="input-group-text">ج.م</span>
                            </div>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">التكرار <span class="text-danger">*</span></label>
                            <select name="frequency"
                                    class="form-select @error('frequency') is-invalid @enderror" required>
                                <option value="">اختر التكرار...</option>
                                <option value="monthly"     {{ old('frequency') === 'monthly'     ? 'selected' : '' }}>شهري</option>
                                <option value="quarterly"   {{ old('frequency') === 'quarterly'   ? 'selected' : '' }}>ربع سنوي</option>
                                <option value="semi_annual" {{ old('frequency') === 'semi_annual' ? 'selected' : '' }}>نصف سنوي</option>
                                <option value="annual"      {{ old('frequency') === 'annual'      ? 'selected' : '' }}>سنوي</option>
                            </select>
                            @error('frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">الوصف</label>
                            <textarea name="description" rows="2"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="وصف الخطة...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active_modal"
                                       class="form-check-input" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active_modal" class="form-check-label fw-600">خطة نشطة</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-color:var(--glass-border);">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>حفظ الخطة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('addFeePlanModal')).show();
    });
</script>
@endpush
@endif
@endsection
