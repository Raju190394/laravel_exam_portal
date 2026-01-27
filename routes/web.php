<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Student\StudentExamController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Exam System Routes - Admin
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::resource('exams', ExamController::class);
        Route::post('exams/{exam}/import', [ExamController::class, 'import'])->name('exams.import');
        Route::resource('courses', CourseController::class);
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::resource('students', StudentController::class);
        
        // Reports
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/exam/{exam}', [\App\Http\Controllers\Admin\ReportController::class, 'examReport'])->name('reports.exam');
        Route::get('reports/exam/{exam}/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('reports.exam.excel');
        Route::get('reports/exam/{exam}/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.exam.pdf');
    });

    // Exam System Routes - Student
    Route::prefix('student')->name('student.')->group(function() {
        Route::get('exams', [StudentExamController::class, 'index'])->name('exams.index');
        Route::get('exams/{exam}/take', [StudentExamController::class, 'show'])->name('exams.take');
        Route::post('exams/{studentExam}/submit-answer', [StudentExamController::class, 'submitAnswer'])->name('exams.submit_answer');
        Route::post('exams/{studentExam}/complete', [StudentExamController::class, 'complete'])->name('exams.complete');
        Route::post('exams/{studentExam}/log-violation', [StudentExamController::class, 'logViolation'])->name('exams.log_violation');
        Route::get('results/{studentExam}', [StudentExamController::class, 'result'])->name('results.show');
        Route::get('results/{studentExam}/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'studentResultPdf'])->name('results.pdf');
    });

    Route::fallback(function() {
        return view('pages/utility/404');
    });    
});
