<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // 🟢 تسجيل فدورة
    public function enroll($courseId)
    {
        $user = Auth::user();

        // تأكد واش الدورة كاينة
        $course = Course::findOrFail($courseId);

        // منع التكرار
        $exists = Enrollment::where('user_id', $user->id)
                            ->where('course_id', $courseId)
                            ->exists();

        if ($exists) {
            return response()->json(['message' => 'You are already enrolled in this course.'], 409);
        }

        // إنشاء التسجيل
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Enrolled successfully.', 'data' => $enrollment], 201);
    }

    // 🟢 عرض دورات المستخدم
    public function myCourses()
{
    $user = Auth::user();

    $courses = Enrollment::with('course.user') // تأكد من أنك تجلب المدرب مع الدورة
                ->where('user_id', $user->id)
                ->get()
                ->pluck('course');

    return response()->json($courses);
}
  


    public function updateStatus(Request $request, $enrollmentId)
{
    $enrollment = Enrollment::findOrFail($enrollmentId);

    // تأكد من صلاحية المستخدم
    if ($enrollment->user_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // التأكد من أن الحالة المرسلة صالحة
    $validStatuses = ['pending', 'confirmed', 'completed'];
    if (!in_array($request->input('status'), $validStatuses)) {
        return response()->json(['message' => 'Invalid status'], 400);
    }

    $enrollment->status = $request->input('status', 'confirmed');
    $enrollment->save();

    return response()->json(['message' => 'Enrollment status updated.', 'data' => $enrollment]);
}

} 
