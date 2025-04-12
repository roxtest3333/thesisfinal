<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\StudentRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\Auth\StudentForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SchoolYearSemesterController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\FileRequirementController;
use App\Http\Controllers\Student\FileRequestsController;


// Email verification routes
Route::get('/email/verify/{id}/{hash}', [LoginController::class, 'verifyEmail'])
    // Remove the signed middleware for now
    ->name('verification.verify');

Route::post('/email/verification-notification', [LoginController::class, 'resendVerificationEmail'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::get('/forgot-password', [StudentForgotPasswordController::class, 'showForgotPasswordForm'])->name('student.password.request');
Route::post('/forgot-password', [StudentForgotPasswordController::class, 'sendResetLink'])->name('student.password.email');
Route::get('/reset-password/{token}', [StudentForgotPasswordController::class, 'showResetForm'])->name('password.reset');  
Route::post('/reset-password', [StudentForgotPasswordController::class, 'resetPassword'])->name('student.password.update');
    
// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication Routes for Guests
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [StudentRegisterController::class, 'register']);  
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth:web'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/schedules/today', [DashboardController::class, 'todayAppointments'])->name('admin.schedules.today');
        Route::get('/schedules/weekly', [DashboardController::class, 'weeklyAppointments'])->name('admin.schedules.weekly');
        Route::get('/schedules/monthly', [DashboardController::class, 'monthlyAppointments'])->name('admin.schedules.monthly');
        
    // Schedule Management
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
        Route::get('/schedules/pending', [ScheduleController::class, 'approvedSchedules'])->name('admin.schedules.approved');
        Route::get('/schedules/pending', [ScheduleController::class, 'rejectedSchedules'])->name('admin.schedules.rejected');
        Route::patch('/schedules/{schedule}/approve', [ScheduleController::class, 'approve'])->name('schedules.approve');
        Route::patch('/schedules/{schedule}/reject', [ScheduleController::class, 'reject'])->name('schedules.reject');
        Route::get('/api/schedule/weekly', [ScheduleController::class, 'getWeeklySchedule'])->name('api.schedule.weekly');
        Route::get('/schedules/date-range', [ScheduleController::class, 'getSchedulesByDateRange'])->name('admin.schedules.date-range');
        Route::get('/schedules/completed', [ScheduleController::class, 'completedSchedules'])->name('admin.schedules.completed');
        Route::post('/schedules/{schedule}/complete', [ScheduleController::class, 'complete'])->name('schedules.complete');
        Route::get('/schedules/semesters', [ScheduleController::class, 'getSemesters'])->name('admin.schedules.get-semesters');
        
    //admin filerequirements
        Route::get('/file-requirements', [FileRequirementController::class, 'index'])->name('admin.file-requirements.index');
        Route::get('/file-requirements/create', [FileRequirementController::class, 'create'])->name('admin.file-requirements.create');
        Route::post('/file-requirements', [FileRequirementController::class, 'store'])->name('admin.file-requirements.store');
        Route::get('/file-requirements/{fileRequirement}/edit', [FileRequirementController::class, 'edit'])->name('admin.file-requirements.edit');
        Route::put('/file-requirements/{fileRequirement}', [FileRequirementController::class, 'update'])->name('admin.file-requirements.update');
        Route::delete('/file-requirements/{fileRequirement}', [FileRequirementController::class, 'destroy'])->name('admin.file-requirements.destroy');

    // Report Management
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('admin.reports.export');
        Route::get('/reports/student/{id}', [ReportController::class, 'studentReport'])->name('admin.reports.student');


    // Student Management
    Route::resource('students', StudentController::class)->names([
        'index' => 'admin.students.index',
        'create' => 'admin.students.create',
        'store' => 'admin.students.store',
        'edit' => 'admin.students.edit',
        'update' => 'admin.students.update',
        'destroy' => 'admin.students.destroy',
    ]);

    // File Management
    Route::resource('files', FileController::class)->names([
        'index' => 'admin.files.index',
        'create' => 'admin.files.create',
        'store' => 'admin.files.store',
        'edit' => 'admin.files.edit',
        'update' => 'admin.files.update',
        'destroy' => 'admin.files.destroy',
    ]);
    
    
    // Settings Management
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

   // School Year
        Route::get('/school-years-semesters', [SchoolYearSemesterController::class, 'index'])->name('admin.school-years-semesters.index');
        Route::post('/school-years', [SchoolYearSemesterController::class, 'storeSchoolYear'])->name('admin.school-years.store');
        Route::delete('/school-years/{id}', [SchoolYearSemesterController::class, 'destroySchoolYear'])->name('admin.school-years.destroy');
    // Semester Routes
        Route::post('/semesters', [SchoolYearSemesterController::class, 'storeSemester'])->name('admin.semesters.store');
        Route::delete('/semesters/{id}', [SchoolYearSemesterController::class, 'destroySemester'])->name('admin.semesters.destroy');

    
    Route::middleware(['superadmin'])->group(function () {
            Route::get('/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
            Route::post('/register', [AdminRegisterController::class, 'register']);
            });
});

// Student Routes
Route::middleware(['auth:student'])->prefix('student')->group(function () {
    //file requirements
        Route::get('/file-requests/create', [FileRequestsController::class, 'create'])->name('student.file_requests.create');
        Route::post('/file-requests', [FileRequestsController::class, 'store'])->name('student.file_requests.store');
        Route::delete('/file-requests/{id}/cancel', [FileRequestsController::class, 'cancelRequest'])->name('file-requests.cancel');
        Route::get('/file-requirements/{id}', [FileRequestsController::class, 'getRequirements'])->name('student.file-requirements.get');
    
    // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    // Request History
        Route::get('/history', [StudentDashboardController::class, 'studentHistory'])->name('student.history');
        Route::get('/history/sort/{sortBy}', [StudentDashboardController::class, 'sortRequests'])->name('student.requests.sort');
    // Request Management
        Route::delete('/requests/{id}/cancel', [StudentDashboardController::class, 'cancelRequest'])->name('student.schedules.cancel');

        Route::get('/profile', [StudentProfileController::class, 'show'])->name('student.profile.show');
        Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
        Route::put('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
       
});   


        Route::get('/csrf-token', function() {
        return csrf_token();
});
    
     