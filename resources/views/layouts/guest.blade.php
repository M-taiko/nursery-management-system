<!DOCTYPE html>
<html lang="ar" dir="rtl" x-data="{ darkMode: $persist(false).as('theme-dark') }" :data-theme="darkMode ? 'dark' : 'light'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة الحضانة')</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body x-init="$store.theme.init()" style="min-height:100vh;overflow-x:hidden;">

    <!-- Animated Background -->
    <div style="position:fixed;inset:0;background:linear-gradient(135deg,#1e1b4b 0%,#312e81 40%,#4c1d95 70%,#1e1b4b 100%);z-index:-2;"></div>
    <!-- Floating Blobs -->
    <div style="position:fixed;top:-20%;left:-10%;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,0.3) 0%,transparent 70%);animation:rotate 25s linear infinite;z-index:-1;"></div>
    <div style="position:fixed;bottom:-20%;right:-10%;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(139,92,246,0.25) 0%,transparent 70%);animation:rotate 20s linear infinite reverse;z-index:-1;"></div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-4">
            <div class="col-md-5 col-lg-4 col-xl-3">

                <!-- Logo -->
                <div class="text-center mb-4" data-aos="fade-down">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width:72px;height:72px;background:rgba(255,255,255,0.15);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);">
                        <i class="bi bi-mortarboard-fill text-warning" style="font-size:2rem;"></i>
                    </div>
                    <h2 class="text-white fw-700 mb-1">نظام إدارة الحضانة</h2>
                    <p class="mb-0" style="color:rgba(199,210,254,0.75);font-size:0.875rem;">مرحباً بك في النظام المتكامل</p>
                </div>

                <!-- Auth Card -->
                <div class="glass-card glass-card-no-hover" data-aos="fade-up" data-aos-delay="100"
                     style="background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.15);">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Theme Toggle -->
                <div class="text-center mt-3">
                    <button @click="darkMode = !darkMode" class="btn btn-sm"
                            style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:rgba(199,210,254,0.8);">
                        <i :class="darkMode ? 'bi-sun-fill' : 'bi-moon-fill'" class="me-1"></i>
                        <span x-text="darkMode ? 'وضع النهار' : 'الوضع الليلي'" style="font-size:0.8rem;"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
