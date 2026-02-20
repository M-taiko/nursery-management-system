<?php

use App\Http\Controllers\Admin\ChildController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\StageController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParentPortal\ParentDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teacher\BehaviorController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\PhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');

    // ========================================
    // Admin Routes
    // ========================================
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {

        // Users
        Route::resource('users', UserController::class);

        // Children
        Route::resource('children', ChildController::class);

        // Stages
        Route::resource('stages', StageController::class)->except('show');

        // Classrooms
        Route::resource('classrooms', ClassroomController::class);

        // Subjects
        Route::resource('subjects', SubjectController::class)->except('show');

        // Fees
        Route::get('fees', [FeeController::class, 'index'])->name('fees.index');
        Route::get('fees/create', [FeeController::class, 'create'])->name('fees.create');
        Route::post('fees', [FeeController::class, 'store'])->name('fees.store');
        Route::get('fees/overdue/list', [FeeController::class, 'overdue'])->name('fees.overdue');
        Route::post('fees/overdue/remind', [FeeController::class, 'sendOverdueReminders'])->name('fees.remind');
        Route::get('fees/{invoice}', [FeeController::class, 'show'])->name('fees.show');
        Route::post('fees/payment', [FeeController::class, 'addPayment'])->name('fees.payment');

        // Fee Plans
        Route::get('fee-plans', [FeeController::class, 'feePlans'])->name('fee-plans.index');
        Route::post('fee-plans', [FeeController::class, 'storeFeePlan'])->name('fee-plans.store');
    });

    // ========================================
    // Teacher Routes
    // ========================================
    Route::middleware(['role:Teacher'])->prefix('teacher')->name('teacher.')->group(function () {

        // Classrooms
        Route::get('classrooms', function() {
            $teacher = auth()->user();
            $classrooms = $teacher->teacherClassrooms()->distinct()->withCount('children')->get();
            return view('teacher.classrooms.index', compact('classrooms'));
        })->name('classrooms.index');

        // Children
        Route::get('children', function() {
            $teacher = auth()->user();
            $classrooms = $teacher->teacherClassrooms()->pluck('classrooms.id');
            $children = \App\Models\Child::whereIn('classroom_id', $classrooms)
                ->with(['stage', 'classroom', 'parent'])
                ->where('status', 'active')
                ->paginate(20);
            return view('teacher.children.index', compact('children'));
        })->name('children.index');

        // Evaluations
        Route::get('evaluations/daily-report', [EvaluationController::class, 'dailyReport'])->name('evaluations.daily-report');
        Route::resource('evaluations', EvaluationController::class)->except(['show', 'destroy']);

        // Photos
        Route::get('photos', [PhotoController::class, 'index'])->name('photos.index');
        Route::get('photos/create', [PhotoController::class, 'create'])->name('photos.create');
        Route::post('photos', [PhotoController::class, 'store'])->name('photos.store');
        Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

        // Behavior
        Route::resource('behavior', BehaviorController::class)->except(['show', 'destroy']);
    });

    // ========================================
    // Parent Routes
    // ========================================
    Route::middleware(['role:Parent'])->prefix('parent')->name('parent.')->group(function () {

        Route::get('children', [ParentDashboardController::class, 'children'])->name('children.index');
        Route::get('children/{child}', [ParentDashboardController::class, 'childProfile'])->name('children.show');
        Route::get('children/{child}/evaluations', [ParentDashboardController::class, 'evaluations'])->name('evaluations.index');
        Route::get('children/{child}/photos', [ParentDashboardController::class, 'photos'])->name('photos.index');
        Route::get('children/{child}/behavior', [ParentDashboardController::class, 'behavior'])->name('behavior.index');
        Route::get('invoices', [ParentDashboardController::class, 'invoices'])->name('invoices.index');
        Route::get('invoices/{invoice}', [ParentDashboardController::class, 'invoiceDetail'])->name('invoices.show');
        Route::get('my-notifications', [ParentDashboardController::class, 'notifications'])->name('notifications.index');
        Route::post('my-notifications/{id}/read', [ParentDashboardController::class, 'markNotificationRead'])->name('notifications.read');
    });
});

require __DIR__ . '/auth.php';
