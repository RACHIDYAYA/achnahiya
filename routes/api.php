<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\Api\EnrollmentController;

// 🔓 Routes العمومية (بدون توكن)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔒 Routes المحمية بالتوكن
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // 🟢 Routes الخاصة بالمواد (لازم تجي قبل apiResource)
    Route::get('/courses/{id}/materials', [CourseController::class, 'materials']);
    Route::post('/courses/{id}/materials', [CourseController::class, 'addMaterial']);

    // 🟡 RESTful API للدورات
    Route::apiResource('courses', CourseController::class);

    // 🧑‍🎓 Enrollment & Courses
    Route::post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll']);
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);
    Route::patch('/enrollments/{id}/status', [EnrollmentController::class, 'updateStatus']);
});
