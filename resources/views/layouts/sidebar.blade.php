@php $user = auth()->user(); @endphp

<aside id="sidebar" class="sidebar" x-data x-bind:class="{ 'show': $store.sidebar.open }">
    <!-- Logo -->
    <div class="sidebar-logo" data-aos="fade-down" data-aos-delay="100">
        <div class="logo-icon">
            <img src="{{ asset('images/logo.jpg') }}" alt="شعار الحضانة"
                 style="width:44px;height:44px;object-fit:contain;border-radius:10px;">
        </div>
        <div class="logo-text">
            <h1>الحضانة</h1>
            <p>نظام الإدارة المتكامل</p>
        </div>
    </div>

    <!-- User Info -->
    <div class="sidebar-user glass-card" data-aos="fade-down" data-aos-delay="200">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="hover-scale">
        <div>
            <div class="user-name">{{ $user->name }}</div>
            <div class="user-role">
                <i class="bi bi-shield-check me-1"></i>
                {{ $user->roles->first()?->name }}
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>لوحة التحكم</span>
                </a>
            </li>

            @role('Admin')
            <li><div class="nav-section-title">الإدارة</div></li>

            <li class="nav-item">
                <a href="{{ route('admin.children.index') }}" class="nav-link {{ request()->routeIs('admin.children.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>الأطفال</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.stages.index') }}" class="nav-link {{ request()->routeIs('admin.stages.*') ? 'active' : '' }}">
                    <i class="bi bi-layers"></i>
                    <span>المراحل</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.classrooms.index') }}" class="nav-link {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>الفصول</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                    <i class="bi bi-book"></i>
                    <span>المواد</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>المستخدمين</span>
                </a>
            </li>

            <li><div class="nav-section-title">المالية</div></li>
            <li class="nav-item">
                <a href="{{ route('admin.fees.index') }}" class="nav-link {{ request()->routeIs('admin.fees.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>المصروفات</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.fee-plans.index') }}" class="nav-link {{ request()->routeIs('admin.fee-plans.*') ? 'active' : '' }}">
                    <i class="bi bi-calculator"></i>
                    <span>خطط الرسوم</span>
                </a>
            </li>
            @endrole

            @role('Teacher')
            <li><div class="nav-section-title">المدرس</div></li>
            <li class="nav-item">
                <a href="{{ route('teacher.evaluations.index') }}" class="nav-link {{ request()->routeIs('teacher.evaluations.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i>
                    <span>التقييمات اليومية</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.photos.index') }}" class="nav-link {{ request()->routeIs('teacher.photos.*') ? 'active' : '' }}">
                    <i class="bi bi-camera"></i>
                    <span>الصور</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teacher.behavior.index') }}" class="nav-link {{ request()->routeIs('teacher.behavior.*') ? 'active' : '' }}">
                    <i class="bi bi-emoji-smile"></i>
                    <span>سجل السلوك</span>
                </a>
            </li>
            @endrole

            @role('Parent')
            <li><div class="nav-section-title">ولي الأمر</div></li>
            <li class="nav-item">
                <a href="{{ route('parent.children.index') }}" class="nav-link {{ request()->routeIs('parent.children.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>أطفالي</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('parent.invoices.index') }}" class="nav-link {{ request()->routeIs('parent.invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>الفواتير</span>
                </a>
            </li>
            @endrole

            <li><div class="nav-section-title">عام</div></li>
            <li class="nav-item">
                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span>الإشعارات</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>الملف الشخصي</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </div>
</aside>
