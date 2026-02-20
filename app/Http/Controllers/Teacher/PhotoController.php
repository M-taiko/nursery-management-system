<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChildPhotoRequest;
use App\Models\Child;
use App\Models\ChildPhoto;
use App\Models\Classroom;
use App\Notifications\NewPhotoUploadedNotification;
use App\Services\PhotoService;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function __construct(private PhotoService $photoService) {}

    public function index(Request $request)
    {
        $photos = $this->photoService->getPhotos($request->all());
        $teacher = $request->user();
        $classrooms = $teacher->teacherClassrooms()->distinct()->get();

        return view('teacher.photos.index', compact('photos', 'classrooms'));
    }

    public function create(Request $request)
    {
        $teacher = $request->user();
        $classrooms = $teacher->teacherClassrooms()->distinct()->get();

        $children = collect();
        if ($request->filled('classroom_id')) {
            $children = Child::where('classroom_id', $request->classroom_id)
                ->where('status', 'active')
                ->where('photo_consent', true)
                ->get();
        }

        return view('teacher.photos.create', compact('classrooms', 'children'));
    }

    public function store(StoreChildPhotoRequest $request)
    {
        $child = Child::findOrFail($request->child_id);

        if (!$child->photo_consent) {
            return redirect()->back()
                ->with('error', 'ولي الأمر لم يوافق على التصوير لهذا الطفل');
        }

        $photos = $this->photoService->uploadPhotos($request->validated(), $request->user()->id);

        foreach ($photos as $photo) {
            $photo->load('child.parent');
            $photo->child->parent->notify(new NewPhotoUploadedNotification($photo));
        }

        return redirect()->route('teacher.photos.index')
            ->with('success', 'تم رفع الصور بنجاح');
    }

    public function destroy(ChildPhoto $photo)
    {
        $this->authorize('delete', $photo);
        $this->photoService->deletePhoto($photo);

        return redirect()->route('teacher.photos.index')
            ->with('success', 'تم حذف الصورة بنجاح');
    }
}
