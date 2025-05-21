<?php
namespace App\Http\Controllers\API;

use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{

  
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    // عرض جميع الدورات
    public function index()
    {
        return response()->json(Course::with('user')->get());
    }

    // إضافة دورة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'nullable|string',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
    
        // 👇 هنا نضيف user_id يدويًا من المستخدم المصادق عليه
        $validated['user_id'] = auth()->id();
    
        $course = Course::create($validated);
    
        return response()->json($course, 201);
    }
    

    // عرض دورة معينة
    public function show($id)
    {
        $course = Course::with('user')->findOrFail($id);
        return response()->json($course);
    }


    
    // تحديث دورة
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // التأكد من أن المستخدم هو نفسه الذي أنشأ الدورة
        if ($course->user_id != auth()->id()) {
            return response()->json(['message' => 'You are not authorized to update this course.'], 403);
        }

        $course->update($request->all());
        return response()->json($course);
    }

    // حذف دورة
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // التأكد من أن المستخدم هو نفسه الذي أنشأ الدورة
        if ($course->user_id != auth()->id()) {
            return response()->json(['message' => 'You are not authorized to delete this course.'], 403);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted']);
    }


    public function addMaterial(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
    
        if ($course->user_id != auth()->id()) {
            return response()->json(['message' => 'غير مصرح لك بإضافة محتوى لهذه الدورة.'], 403);
        }
    
        $validated = $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:video,pdf',
            'file' => 'required|file|mimes:mp4,pdf|max:51200' // Max 50MB
        ]);
    
        $path = $request->file('file')->store('course_materials', 'public');
        \Log::info('File stored at: ' . $path);
    
        $material = CourseMaterial::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => '/storage/' . $path,  // المسار النسبي الصحيح للواجهة
            'course_id' => $courseId
        ]);
    
        return response()->json(['message' => 'تم رفع المادة بنجاح', 'material' => $material], 201);
    }
    
    

}
