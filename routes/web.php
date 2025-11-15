<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClasssController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('enrollment', [UserController::class, 'studentClasses'])->name('student.classes')->middleware('auth','role:student');

Route::middleware(['auth' , 'role:superadmin,admin'])->group(function () {
    Route::view('/admin/dashboard', 'admins.dashboard')->name('dashboard');
    Route::view('/admin/program', 'admins.programs.program_view')->name('admin.program');
    Route::view('/admin/course', 'admins.courses.course_view')->name('admin.course');
    Route::view('/admin/class', 'admins.classes.class_view')->name('admin.class');
    Route::view('/admin/lesson', 'admins.lessons.lesson_view')->name('admin.lesson');

    //Program Management
    Route::get('/admin/program', [ProgramController::class, 'index'])->name('program.index');
    Route::post('/admin/program/store', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/program/{id}/edit', [ProgramController::class, 'edit'])->name('program.edit');
    Route::post('/program/{id}/update', [ProgramController::class, 'update'])->name('program.update');
    Route::patch('/program/{id}/toggle-status', [ProgramController::class, 'toggleStatus'])->name('program.toggleStatus');

    //Course Management
    Route::get('/admin/course', [CourseController::class, 'index'])->name('course.index');
    Route::post('/admin/course/store', [CourseController::class, 'store'])->name('course.store');
    Route::get('/course/{id}/edit', [CourseController::class, 'edit'])->name('course.edit');
    Route::post('/course/{id}/update', [CourseController::class, 'update'])->name('course.update');
    Route::patch('/course/{id}/toggle-status', [CourseController::class, 'toggleStatus'])->name('course.toggleStatus');

    //Class Management
    Route::get('/admin/class', [ClasssController::class, 'index'])->name('class.index');
    Route::post('/admin/class/store', [ClasssController::class, 'store'])->name('class.store');
    Route::get('/class/{id}/edit', [ClasssController::class, 'edit'])->name('class.edit');
    Route::post('/class/{id}/update', [ClasssController::class, 'update'])->name('class.update');
    Route::patch('/class/{id}/toggle-status', [ClasssController::class, 'toggleStatus'])->name('class.toggleStatus');

    //Lesson Management
    Route::get('/admin/lesson', [LessonController::class, 'index'])->name('lesson.index');
    Route::post('/admin/lesson/store', [LessonController::class, 'store'])->name('lesson.store');
    Route::get('/lesson/{id}/edit', [LessonController::class, 'edit'])->name('lesson.edit');
    Route::post('/lesson/{id}/update', [LessonController::class, 'update'])->name('lesson.update');
    Route::patch('/lesson/{id}/toggle-status', [LessonController::class, 'toggleStatus'])->name('lesson.toggleStatus');

    //Quiz Management
    Route::get('/admin/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/admin/quiz/store', [QuizController::class, 'store'])->name('quiz.store');
    Route::get('/quiz/{id}/edit', [QuizController::class, 'edit'])->name('quiz.edit');
    Route::post('/quiz/{id}/update', [QuizController::class, 'update'])->name('quiz.update');
    Route::patch('/quiz/{id}/toggle-status', [QuizController::class, 'toggleStatus'])->name('quiz.toggleStatus');

    //Question Management
    Route::get('/admin/{id}/question', [QuestionController::class, 'index'])->name('question.index');
    Route::post('/question/store', [QuestionController::class, 'store'])->name('question.store');
    Route::get('/question/{id}/edit', [QuestionController::class, 'edit'])->name('question.edit');
    Route::post('/question/{id}/update', [QuestionController::class, 'update'])->name('question.update');
    Route::patch('/question/{id}/toggle-status', [QuestionController::class, 'toggleStatus'])->name('question.toggleStatus');

    //Option Management
    Route::get('/admin/{id}/option', [OptionController::class, 'index'])->name('option.index');
    Route::post('/option/store', [OptionController::class, 'store'])->name('option.store');
    Route::get('/option/{id}/edit', [OptionController::class, 'edit'])->name('option.edit');
    Route::post('/option/{id}/update', [OptionController::class, 'update'])->name('option.update');
    Route::patch('/option/{id}/toggle-status', [OptionController::class, 'toggleStatus'])->name('option.toggleStatus');
    
    //User Management
    Route::get('/admin/users', [UserController::class, 'index'])->name('user.index');

    //Enrollment Management
    Route::get('/admin/enrollments/class/{id}', [EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/admin/enrollments/class/{id}/update', [EnrollmentController::class, 'updateEnrollments'])->name('enrollment.update');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');