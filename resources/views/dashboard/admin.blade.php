@extends('layouts.app')

@section('page-title', 'لوحة تحكم الإدارة')

@section('content')
<!-- Page Header -->
<div class="page-header mb-4" data-aos="fade-down">
    <h2 class="page-title">
        <i class="bi bi-speedometer2 me-2"></i>
        لوحة تحكم الإدارة
    </h2>
    <p class="page-subtitle">مرحباً {{ auth()->user()->name }}، إليك ملخص النظام اليوم</p>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Total Children -->
    <div class="col-md-4 col-xl-2">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="0">
            <div class="stat-icon bg-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" x-data="{ count: 0 }" x-init="() => {
                    let target = {{ $data['total_children'] }};
                    let interval = setInterval(() => {
                        if (count < target) {
                            count++;
                        } else {
                            clearInterval(interval);
                        }
                    }, target > 50 ? 10 : 30);
                }" x-text="count"></h3>
                <p class="stat-label">الأطفال</p>
            </div>
        </div>
    </div>

    <!-- Total Teachers -->
    <div class="col-md-4 col-xl-2">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="100">
            <div class="stat-icon bg-success">
                <i class="bi bi-person-badge"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" x-data="{ count: 0 }" x-init="() => {
                    let target = {{ $data['total_teachers'] }};
                    let interval = setInterval(() => {
                        if (count < target) {
                            count++;
                        } else {
                            clearInterval(interval);
                        }
                    }, target > 50 ? 10 : 30);
                }" x-text="count"></h3>
                <p class="stat-label">المدرسين</p>
            </div>
        </div>
    </div>

    <!-- Total Parents -->
    <div class="col-md-4 col-xl-2">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
            <div class="stat-icon bg-info">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value" x-data="{ count: 0 }" x-init="() => {
                    let target = {{ $data['total_parents'] }};
                    let interval = setInterval(() => {
                        if (count < target) {
                            count++;
                        } else {
                            clearInterval(interval);
                        }
                    }, target > 50 ? 10 : 30);
                }" x-text="count"></h3>
                <p class="stat-label">أولياء الأمور</p>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-md-4 col-xl-2">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="300">
            <div class="stat-icon bg-warning">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ number_format($data['total_revenue'], 0) }}</h3>
                <p class="stat-label">ج.م الإيرادات</p>
            </div>
        </div>
    </div>

    <!-- Pending Invoices -->
    <div class="col-md-4 col-xl-2">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
            <div class="stat-icon bg-secondary">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $data['pending_invoices'] }}</h3>
                <p class="stat-label">فواتير معلقة</p>
            </div>
        </div>
    </div>

    <!-- Overdue Invoices -->
    <div class="col-md-4 col-xl-2">
        <div class="stat-card" data-aos="zoom-in" data-aos-delay="500">
            <div class="stat-icon bg-danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-value">{{ $data['overdue_invoices'] }}</h3>
                <p class="stat-label">فواتير متأخرة</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data -->
<div class="row g-4">
    <!-- Recent Evaluations -->
    <div class="col-lg-6">
        <div class="glass-card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <h5><i class="bi bi-clipboard-check"></i> أحدث التقييمات</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-glass mb-0">
                        <thead>
                            <tr>
                                <th>الطفل</th>
                                <th>المادة</th>
                                <th>المستوى</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recent_evaluations'] as $eval)
                            <tr>
                                <td>{{ $eval->child->name }}</td>
                                <td>{{ $eval->subject->name }}</td>
                                <td><span class="badge-level {{ $eval->understanding_level }}">{{ $eval->understanding_label }}</span></td>
                                <td>{{ $eval->evaluation_date->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <div class="empty-state">
                                        <i class="bi bi-clipboard2-x"></i>
                                        <p>لا توجد تقييمات حتى الآن</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-lg-6">
        <div class="glass-card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
                <h5><i class="bi bi-cash"></i> أحدث المدفوعات</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-glass mb-0">
                        <thead>
                            <tr>
                                <th>رقم الإيصال</th>
                                <th>المبلغ</th>
                                <th>الطريقة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recent_payments'] as $payment)
                            <tr>
                                <td>{{ $payment->receipt_number }}</td>
                                <td>{{ number_format($payment->amount, 2) }} ج.م</td>
                                <td>{{ $payment->payment_method_label }}</td>
                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <div class="empty-state">
                                        <i class="bi bi-cash-stack"></i>
                                        <p>لا توجد مدفوعات حتى الآن</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
