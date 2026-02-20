<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStageRequest;
use App\Models\Stage;
use App\Models\Subject;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::withCount(['classrooms', 'children'])->paginate(15);
        return view('admin.stages.index', compact('stages'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.stages.create', compact('subjects'));
    }

    public function store(StoreStageRequest $request)
    {
        $stage = Stage::create($request->validated());

        if ($request->has('subjects')) {
            $stage->subjects()->sync($request->subjects);
        }

        return redirect()->route('admin.stages.index')
            ->with('success', 'تم إضافة المرحلة بنجاح');
    }

    public function edit(Stage $stage)
    {
        $subjects = Subject::where('is_active', true)->get();
        $stage->load('subjects');
        return view('admin.stages.edit', compact('stage', 'subjects'));
    }

    public function update(StoreStageRequest $request, Stage $stage)
    {
        $stage->update($request->validated());

        if ($request->has('subjects')) {
            $stage->subjects()->sync($request->subjects);
        }

        return redirect()->route('admin.stages.index')
            ->with('success', 'تم تحديث المرحلة بنجاح');
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();
        return redirect()->route('admin.stages.index')
            ->with('success', 'تم حذف المرحلة بنجاح');
    }
}
