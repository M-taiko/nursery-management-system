<x-guest-layout>
    <div class="text-center mb-4">
        <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="bi bi-envelope-check text-white" style="font-size:1.8rem;"></i>
        </div>
        <h4 class="fw-bold mb-2">تحقق من بريدك الإلكتروني</h4>
        <p class="text-muted small">
            شكراً للتسجيل! يرجى تأكيد بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه إليك.
        </p>
    </div>

    @if(session('status') == 'verification-link-sent')
    <div class="alert alert-success rounded-3 mb-3">
        <i class="bi bi-check-circle me-2"></i>
        تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.
    </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-send me-2"></i>إعادة إرسال رابط التحقق
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100">
            <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
        </button>
    </form>
</x-guest-layout>
