<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->group(function () {
    Route::resource('courses', CourseController::class)->except(['create', 'edit']);
    Route::resource('sections', SectionController::class)->except(['create', 'edit']);
    Route::resource('students', StudentController::class)->except(['create', 'edit']);
    Route::resource('instructors', InstructorController::class)->except(['create', 'edit']);
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::middleware('auth:student')->group(function () {
    Route::get('student-courses', [CourseController::class, 'index']);
    Route::get('student-courses/{course}/sections', [CourseController::class, 'sections']);
    Route::post('student-courses/enroll', [StudentController::class, 'enroll']);
    Route::get('students/my-courses/list', [StudentController::class, 'myCourses']);
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
