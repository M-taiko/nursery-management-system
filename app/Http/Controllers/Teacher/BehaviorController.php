<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBehaviorRecordRequest;
use App\Models\BehaviorRecord;
use App\Models\Child;
use App\Notifications\NewBehaviorRecordNotification;
use Illuminate\Http\Request;

class BehaviorController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user();
        $records = BehaviorRecord::with(['child', 'teacher'])
            ->where('teacher_id', $teacher->id)
            ->when($request->filled('child_id'), fn($q) => $q->where('child_id', $request->child_id))
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->type))
            ->latest('record_date')
            ->paginate(15);

        return view('teacher.behavior.index', compact('records'));
    }

    public function create(Request $request)
    {
        $teacher = $request->user();
        $classrooms = $teacher->teacherClassrooms()->distinct()->get();

        $children = collect();
        if ($request->filled('classroom_id')) {
            $children = Child::where('classroom_id', $request->classroom_id)
                ->where('status', 'active')
                ->get();
        }

        return view('teacher.behavior.create', compact('classrooms', 'children'));
    }

    public function store(StoreBehaviorRecordRequest $request)
    {
        $record = BehaviorRecord::create(array_merge(
            $request->validated(),
            ['teacher_id' => $request->user()->id]
        ));

        $record->load('child.parent');
        $record->child->parent->notify(new NewBehaviorRecordNotification($record));

        $record->update(['parent_notified' => true]);

        return redirect()->route('teacher.behavior.index')
            ->with('success', 'تم تسجيل السلوك بنجاح');
    }

    public function edit(BehaviorRecord $record)
    {
        $this->authorize('update', $record);
        return view('teacher.behavior.edit', compact('record'));
    }

    public function update(StoreBehaviorRecordRequest $request, BehaviorRecord $record)
    {
        $this->authorize('update', $record);
        $record->update($request->validated());

        return redirect()->route('teacher.behavior.index')
            ->with('success', 'تم تحديث السجل بنجاح');
    }
}
