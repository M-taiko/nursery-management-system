<?php

namespace App\Services;

use App\Models\BehaviorRecord;
use App\Models\Child;
use App\Models\DailyEvaluation;
use App\Models\Payment;
use App\Models\FeeInvoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getAdminDashboard(): array
    {
        $totalChildren = Child::where('status', 'active')->count();
        $totalTeachers = User::role('Teacher')->count();
        $totalParents = User::role('Parent')->count();
        $totalRevenue = Payment::sum('amount');
        $pendingInvoices = FeeInvoice::where('status', 'pending')->count();
        $overdueInvoices = FeeInvoice::overdue()->count();

        $recentEvaluations = DailyEvaluation::with(['child', 'subject', 'teacher'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['feeInvoice.child', 'receiver'])
            ->latest()
            ->take(5)
            ->get();

        return [
            'total_children' => $totalChildren,
            'total_teachers' => $totalTeachers,
            'total_parents' => $totalParents,
            'total_revenue' => $totalRevenue,
            'pending_invoices' => $pendingInvoices,
            'overdue_invoices' => $overdueInvoices,
            'recent_evaluations' => $recentEvaluations,
            'recent_payments' => $recentPayments,
        ];
    }

    public function getTeacherDashboard($teacherId): array
    {
        $teacher = User::findOrFail($teacherId);

        $myClassrooms = $teacher->teacherClassrooms()->distinct()->withCount('children')->get();
        $myStudents = Child::whereIn('classroom_id', $myClassrooms->pluck('id'))
            ->where('status', 'active')
            ->count();

        $todayEvaluations = DailyEvaluation::where('teacher_id', $teacherId)
            ->whereDate('evaluation_date', today())
            ->count();

        $uploadedPhotos = \App\Models\ChildPhoto::where('uploaded_by', $teacherId)->count();

        $recentEvaluations = DailyEvaluation::where('teacher_id', $teacherId)
            ->with(['child', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        return [
            'my_classrooms' => $myClassrooms->count(),
            'my_students' => $myStudents,
            'today_evaluations' => $todayEvaluations,
            'uploaded_photos' => $uploadedPhotos,
            'classrooms' => $myClassrooms,
            'recent_evaluations' => $recentEvaluations,
        ];
    }

    public function getParentDashboard($parentId): array
    {
        $myChildren = Child::where('parent_id', $parentId)
            ->with(['stage', 'classroom'])
            ->get();

        $childrenIds = $myChildren->pluck('id');

        $totalEvaluations = DailyEvaluation::whereIn('child_id', $childrenIds)->count();
        $totalPhotos = \App\Models\ChildPhoto::whereIn('child_id', $childrenIds)->count();

        $pendingInvoicesList = FeeInvoice::whereIn('child_id', $childrenIds)
            ->whereIn('status', ['pending', 'partial'])
            ->with(['child'])
            ->latest()
            ->take(5)
            ->get();

        $pendingInvoicesCount = FeeInvoice::whereIn('child_id', $childrenIds)
            ->whereIn('status', ['pending', 'partial'])
            ->count();

        // remaining_amount is a PHP accessor (not a DB column), calculate via raw SQL
        $totalDue = FeeInvoice::whereIn('child_id', $childrenIds)
            ->whereIn('status', ['pending', 'partial'])
            ->selectRaw('COALESCE(SUM(total - COALESCE((SELECT SUM(amount) FROM payments WHERE fee_invoice_id = fee_invoices.id), 0)), 0) as total_remaining')
            ->value('total_remaining') ?? 0;

        $recentEvaluations = DailyEvaluation::whereIn('child_id', $childrenIds)
            ->with(['child', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        $recentPhotos = \App\Models\ChildPhoto::whereIn('child_id', $childrenIds)
            ->latest()
            ->take(12)
            ->get();

        return [
            'children' => $myChildren,
            'total_evaluations' => $totalEvaluations,
            'total_photos' => $totalPhotos,
            'pending_invoices' => $pendingInvoicesCount,
            'total_due' => $totalDue,
            'recent_evaluations' => $recentEvaluations,
            'recent_photos' => $recentPhotos,
            'pending_invoices_list' => $pendingInvoicesList,
        ];
    }
}
