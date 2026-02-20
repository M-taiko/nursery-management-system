<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with('stage')
            ->withCount('children')
            ->paginate(15);

        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        $stages = Stage::where('is_active', true)->get();
        return view('admin.classrooms.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'تم إضافة الفصل بنجاح');
    }

    public function show(Classroom $classroom)
    {
        $classroom->load(['stage', 'children.parent', 'teachers']);
        return view('admin.classrooms.show', compact('classroom'));
    }

    public function edit(Classroom $classroom)
    {
        $stages = Stage::where('is_active', true)->get();
        return view('admin.classrooms.edit', compact('classroom', 'stages'));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id',
            'capacity' => 'required|integer|min:1|max:50',
            'is_active' => 'boolean',
        ]);

        $classroom->update($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'تم تحديث الفصل بنجاح');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('admin.classrooms.index')
            ->with('success', 'تم حذف الفصل بنجاح');
    }
}
