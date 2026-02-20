<!DOCTYPE html>
<html lang="ar" dir="rtl" x-data="{ darkMode: $persist(false).as('theme-dark') }" :data-theme="darkMode ? 'dark' : 'light'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" :content="darkMode ? '#0f172a' : '#4e73df'">
    <title>@yield('title', 'نظام إدارة الحضانة')</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>
<body x-init="$store.theme.init()">
    <!-- Animated Background -->
    <div class="app-background"></div>

    <div class="app-wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="sidebar-overlay" x-bind:class="{ 'show': $store.sidebar.open }" @click="$store.sidebar.close()"></div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Navigation -->
            @include('layouts.topnav')

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="container-fluid px-4 pt-3">
                    <div class="alert alert-success alert-dismissible fade show alert-modern" role="alert" data-aos="fade-down">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container-fluid px-4 pt-3">
                    <div class="alert alert-danger alert-dismissible fade show alert-modern" role="alert" data-aos="fade-down">
                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer glass-card">
                <span class="text-muted small">
                    نظام إدارة الحضانة &copy; {{ date('Y') }} &mdash;
                    تم التطوير بواسطة <a href="https://masarsoft.io" target="_blank" rel="noopener" style="color:var(--primary);text-decoration:none;font-weight:600;">masarsoft.io</a>
                </span>
                <button @click="darkMode = !darkMode" class="btn-icon btn-sm" title="تبديل الوضع">
                    <i :class="darkMode ? 'bi-sun-fill' : 'bi-moon-fill'"></i>
                </button>
            </footer>
        </div>
    </div>
</body>
</html>
