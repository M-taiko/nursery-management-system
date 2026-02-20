<x-guest-layout>
    <h4 class="fw-bold mb-1 text-center">نسيت كلمة المرور؟</h4>
    <p class="text-muted small text-center mb-4">
        أدخل بريدك الإلكتروني وسنرسل لك رابط لإعادة التعيين
    </p>

    @if(session('status'))
    <div class="alert alert-success mb-3 rounded-3">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus placeholder="example@email.com">
            </div>
            @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
            <i class="bi bi-send me-2"></i>إرسال رابط إعادة التعيين
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="small text-decoration-none text-muted">
                <i class="bi bi-arrow-right me-1"></i>العودة لتسجيل الدخول
            </a>
        </div>
    </form>
</x-guest-layout>
