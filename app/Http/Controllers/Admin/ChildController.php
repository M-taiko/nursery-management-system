<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChildRequest;
use App\Http\Requests\UpdateChildRequest;
use App\Models\Child;
use App\Models\Classroom;
use App\Models\Stage;
use App\Models\User;
use App\Services\ChildService;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function __construct(private ChildService $childService) {}

    public function index(Request $request)
    {
        $children = $this->childService->getFilteredChildren($request->all());
        $stages = Stage::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->with('stage')->get();

        return view('admin.children.index', compact('children', 'stages', 'classrooms'));
    }

    public function create()
    {
        $stages = Stage::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->with('stage')->get();
        $parents = User::role('Parent')->where('is_active', true)->get();

        return view('admin.children.create', compact('stages', 'classrooms', 'parents'));
    }

    public function store(StoreChildRequest $request)
    {
        $this->childService->createChild($request->validated());

        return redirect()->route('admin.children.index')
            ->with('success', 'تم إضافة الطفل بنجاح');
    }

    public function show(Child $child)
    {
        $child = $this->childService->getChildWithRelations($child->id);
        return view('admin.children.show', compact('child'));
    }

    public function edit(Child $child)
    {
        $stages = Stage::where('is_active', true)->get();
        $classrooms = Classroom::where('is_active', true)->with('stage')->get();
        $parents = User::role('Parent')->where('is_active', true)->get();

        return view('admin.children.edit', compact('child', 'stages', 'classrooms', 'parents'));
    }

    public function update(UpdateChildRequest $request, Child $child)
    {
        $this->childService->updateChild($child, $request->validated());

        return redirect()->route('admin.children.show', $child)
            ->with('success', 'تم تحديث بيانات الطفل بنجاح');
    }

    public function destroy(Child $child)
    {
        $this->childService->deleteChild($child);

        return redirect()->route('admin.children.index')
            ->with('success', 'تم حذف الطفل بنجاح');
    }
}
