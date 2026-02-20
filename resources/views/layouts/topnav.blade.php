<header class="top-navbar glass-navbar sticky-top">
    <!-- Mobile menu button -->
    <button @click="$store.sidebar.toggle()" class="btn-icon d-lg-none">
        <i class="bi bi-list fs-4"></i>
    </button>

    <!-- Page Title -->
    <h2 class="page-title d-none d-sm-block">
        @yield('page-title', 'لوحة التحكم')
    </h2>

    <!-- Right Actions -->
    <div class="navbar-actions">
        <!-- Theme Toggle -->
        <button @click="darkMode = !darkMode" class="btn-icon" title="تبديل الوضع المظلم">
            <i :class="darkMode ? 'bi-sun-fill' : 'bi-moon-fill'"></i>
        </button>

        <!-- Notifications Bell -->
        <a href="{{ route('notifications.index') }}" class="notification-bell btn-icon position-relative">
            <i class="bi bi-bell"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="notification-badge animate-pulse">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>

        <!-- User Avatar & Dropdown -->
        <div class="user-dropdown" x-data="{ open: false }">
            <button @click="open = !open" class="user-dropdown-toggle">
                <img src="{{ auth()->user()->avatar_url }}" class="user-avatar" alt="{{ auth()->user()->name }}">
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down ms-1"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="animate-fade-in-down"
                 x-transition:leave="animate-fade-out"
                 class="dropdown-menu-glass"
                 style="display: none;">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="bi bi-person"></i>
                    الملف الشخصي
                </a>
                <a href="{{ route('notifications.index') }}" class="dropdown-item">
                    <i class="bi bi-bell"></i>
                    الإشعارات
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
