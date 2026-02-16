<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClasssController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/check-session', function () {
    return response()->json([
        'host' => request()->getHost(),
        'session_cookie' => config('session.cookie'),
    ]);
});

$mainHost = parse_url(config('app.url'), PHP_URL_HOST);
$adminHost = parse_url(config('app.admin_url'), PHP_URL_HOST);

Route::get('/testui', function () {
    return view('lesson');
});

Auth::routes();

Route::domain('haolin.test')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/courses', [HomeController::class, 'course'])->name('course');
    Route::get('/classes', [HomeController::class, 'class'])->name('class');
    Route::get('/course/{id}/classes',[HomeController::class, 'getCourseClass'])->name('getclass');
    Route::get('/class/{id}/detail',[StudentController::class, 'getClass'])->name('classDetail');
    // normal user routes
    Route::middleware(['auth' , 'role:student'])->group(function () {
        Route::get('/student/classes', [StudentController::class, 'assignedClasses'])->name('student.classes');
        Route::post('/student/class/{id}/enroll', [EnrollmentController::class, 'updateEnrollment'])->name('student.class.enroll');
        Route::get('/class/{id}/lessons', [StudentController::class, 'lessons'])->name('student.class.lessons');
        Route::get('/lesson/{id}/start', [StudentController::class, 'startLesson'])->name('student.lesson.start');
        Route::post('/lesson/{id}/progress', [StudentController::class, 'updateProgress'])->name('student.lesson.progress');
        Route::post('/lesson/completelesson', [StudentController::class, 'completeLesson'])->name('student.lesson.complete');
        Route::get('/lesson/{id}/knowledgeCheck={mode}', [StudentController::class, 'knowledgeCheck'])->name('student.lesson.knowledgeCheck');
        Route::post('/knowledgeCheck/{id}/submit={mode}', [StudentController::class, 'submitKnowledgeCheck'])->name('student.knowledge.submit');
        // Class final quiz
        Route::get('/student/class-quiz/{id}', [StudentController::class, 'showClassQuiz'])->name('student.class.quiz.show');
    });

});

Route::domain('admin.haolin.test')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'] )->name('admin.login');
    // admin routes
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
        Route::get('/admin/{id}/course', [CourseController::class, 'index'])->name('course.index');
        Route::post('/admin/course/store', [CourseController::class, 'store'])->name('course.store');
        Route::get('/course/{id}/edit', [CourseController::class, 'edit'])->name('course.edit');
        Route::post('/course/{id}/update', [CourseController::class, 'update'])->name('course.update');
        Route::patch('/course/{id}/toggle-status', [CourseController::class, 'toggleStatus'])->name('course.toggleStatus');
        
        //Class Management
        Route::get('/admin/{id}/class', [ClasssController::class, 'index'])->name('class.index');
        Route::post('/admin/class/store', [ClasssController::class, 'store'])->name('class.store');
        Route::get('/class/{id}/edit', [ClasssController::class, 'edit'])->name('class.edit');
        Route::post('/class/{id}/update', [ClasssController::class, 'update'])->name('class.update');
        Route::patch('/class/{id}/toggle-status', [ClasssController::class, 'toggleStatus'])->name('class.toggleStatus');
            // Load available courses + next sequence
        Route::get('/class/{id}/load-available-courses', [ClasssController::class, 'loadAvailableCourses'])->name('class.load-courses');

        // Save assignment
        Route::post('/class/{id}/assign-course', [ClasssController::class, 'assignCourse']);
        Route::get('/class/{id}/assigned-courses', [ClasssController::class, 'loadAssignedCourses']);
        Route::post('/class/{class}/soft-unassign-course/{course}', [ClasssController::class, 'softUnassignCourse']);

        //Lesson Management
        Route::get('/admin/{id}/lesson', [LessonController::class, 'index'])->name('lesson.index');
        Route::post('/admin/lesson/store', [LessonController::class, 'store'])->name('lesson.store');
        Route::get('/lesson/{id}/edit', [LessonController::class, 'edit'])->name('lesson.edit');
        Route::post('/lesson/{id}/update', [LessonController::class, 'update'])->name('lesson.update');
        Route::patch('/lesson/{id}/toggle-status', [LessonController::class, 'toggleStatus'])->name('lesson.toggleStatus');
        Route::post('/lesson/sequenceUpdate', [LessonController::class, 'updateSequence'])->name('lesson.updateSequence');

        //Quiz Management
        Route::get('/admin/quiz-builder', [QuizController::class, 'QuizIndex'])->name('quiz.inde');
        Route::get('/admin/quiz-builder/{id}', [QuizController::class, 'getQuiz'])->name('quiz.inde');
        Route::put('/admin/SaveQuiz', [QuizController::class, 'saveQuiz'])->name('quiz.Save');

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
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
