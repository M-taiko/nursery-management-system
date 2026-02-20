<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\BehaviorRecord;
use App\Models\Child;
use App\Models\ChildPhoto;
use App\Models\DailyEvaluation;
use App\Models\FeeInvoice;
use Illuminate\Http\Request;

class ParentDashboardController extends Controller
{
    public function children(Request $request)
    {
        $children = Child::where('parent_id', $request->user()->id)
            ->with(['stage', 'classroom'])
            ->get();

        return view('parent.children.index', compact('children'));
    }

    public function childProfile(Child $child, Request $request)
    {
        abort_if($child->parent_id !== $request->user()->id, 403);

        $child->load(['stage', 'classroom', 'evaluations' => function ($q) {
            $q->latest('evaluation_date')->with('subject', 'teacher')->limit(20);
        }, 'behaviorRecords' => function ($q) {
            $q->latest('record_date')->limit(10);
        }]);

        return view('parent.children.show', compact('child'));
    }

    public function evaluations(Child $child, Request $request)
    {
        abort_if($child->parent_id !== $request->user()->id, 403);

        $evaluations = DailyEvaluation::where('child_id', $child->id)
            ->with(['subject', 'teacher'])
            ->when($request->filled('date'), fn($q) => $q->where('evaluation_date', $request->date))
            ->when($request->filled('subject_id'), fn($q) => $q->where('subject_id', $request->subject_id))
            ->latest('evaluation_date')
            ->paginate(15);

        $subjects = $child->stage->subjects;

        return view('parent.evaluations.index', compact('child', 'evaluations', 'subjects'));
    }

    public function photos(Child $child, Request $request)
    {
        abort_if($child->parent_id !== $request->user()->id, 403);
        abort_if(!$child->photo_consent, 403, 'لم يتم الموافقة على التصوير');

        $photos = ChildPhoto::where('child_id', $child->id)
            ->with('uploader')
            ->latest('photo_date')
            ->paginate(12);

        return view('parent.photos.index', compact('child', 'photos'));
    }

    public function invoices(Request $request)
    {
        $childrenIds = Child::where('parent_id', $request->user()->id)->pluck('id');

        $invoices = FeeInvoice::whereIn('child_id', $childrenIds)
            ->with(['child', 'feePlan', 'payments'])
            ->latest('due_date')
            ->paginate(15);

        return view('parent.fees.index', compact('invoices'));
    }

    public function invoiceDetail(FeeInvoice $invoice, Request $request)
    {
        abort_if($invoice->child->parent_id !== $request->user()->id, 403);

        $invoice->load(['child', 'feePlan', 'payments.receiver']);
        return view('parent.fees.show', compact('invoice'));
    }

    public function behavior(Child $child, Request $request)
    {
        abort_if($child->parent_id !== $request->user()->id, 403);

        $records = BehaviorRecord::where('child_id', $child->id)
            ->with('teacher')
            ->latest('record_date')
            ->paginate(15);

        return view('parent.behavior.index', compact('child', 'records'));
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);
        return view('parent.notifications.index', compact('notifications'));
    }

    public function markNotificationRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'تم تحديد الإشعار كمقروء');
    }
}
