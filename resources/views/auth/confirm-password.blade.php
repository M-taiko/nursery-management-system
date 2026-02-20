<x-guest-layout>
    <div class="text-center mb-4">
        <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--warning),#f59e0b);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-shield-lock text-white" style="font-size:1.8rem;"></i>
        </div>
        <h4 class="fw-bold mb-2">تأكيد كلمة المرور</h4>
        <p class="text-muted small">
            هذه منطقة آمنة. يرجى تأكيد كلمة مرورك قبل المتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">كلمة المرور</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-shield-check me-2"></i>تأكيد
        </button>
    </form>
</x-guest-layout>
