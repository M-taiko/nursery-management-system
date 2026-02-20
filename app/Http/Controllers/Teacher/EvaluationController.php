<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDailyEvaluationRequest;
use App\Models\Child;
use App\Models\Classroom;
use App\Models\DailyEvaluation;
use App\Models\Subject;
use App\Notifications\NewEvaluationNotification;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function __construct(private EvaluationService $evaluationService) {}

    public function index(Request $request)
    {
        $teacher = $request->user();
        $filters = array_merge($request->all(), ['teacher_id' => $teacher->id]);
        $evaluations = $this->evaluationService->getEvaluations($filters);

        $classrooms = $teacher->teacherClassrooms()->distinct()->get();
        $subjects = $teacher->teacherSubjects()->distinct()->get();

        return view('teacher.evaluations.index', compact('evaluations', 'classrooms', 'subjects'));
    }

    public function create(Request $request)
    {
        $teacher = $request->user();
        $classrooms = $teacher->teacherClassrooms()->distinct()->get();
        $subjects = $teacher->teacherSubjects()->distinct()->get();

        $children = collect();
        if ($request->filled('classroom_id')) {
            $children = Child::where('classroom_id', $request->classroom_id)
                ->where('status', 'active')
                ->get();
        }

        return view('teacher.evaluations.create', compact('classrooms', 'subjects', 'children'));
    }

    public function store(StoreDailyEvaluationRequest $request)
    {
        $evaluation = $this->evaluationService->createEvaluation(
            $request->validated(),
            $request->user()->id
        );

        $evaluation->load('child.parent');
        $evaluation->child->parent->notify(new NewEvaluationNotification($evaluation));

        return redirect()->route('teacher.evaluations.index')
            ->with('success', 'تم حفظ التقييم بنجاح');
    }

    public function edit(DailyEvaluation $evaluation)
    {
        $this->authorize('update', $evaluation);
        $subjects = Subject::where('is_active', true)->get();
        return view('teacher.evaluations.edit', compact('evaluation', 'subjects'));
    }

    public function update(StoreDailyEvaluationRequest $request, DailyEvaluation $evaluation)
    {
        $this->authorize('update', $evaluation);
        $this->evaluationService->updateEvaluation($evaluation, $request->validated());

        return redirect()->route('teacher.evaluations.index')
            ->with('success', 'تم تحديث التقييم بنجاح');
    }

    public function dailyReport(Request $request)
    {
        $teacher = $request->user();
        $date = $request->get('date', today()->format('Y-m-d'));
        $classrooms = $teacher->teacherClassrooms()->distinct()->get();
        $classroomId = $request->get('classroom_id', $classrooms->first()?->id);

        $report = [];
        if ($classroomId) {
            $report = $this->evaluationService->getClassroomDailyReport($classroomId, $date);
        }

        return view('teacher.evaluations.daily-report', compact('report', 'classrooms', 'date', 'classroomId'));
    }
}
