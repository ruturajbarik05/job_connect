<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\JobSeekerController;
use App\Http\Controllers\Backend\RecruiterController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/search', [HomeController::class, 'search'])->name('jobs.search');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{slug}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::post('/jobs/{job}/save', [ApplicationController::class, 'saveJob'])->name('jobs.save');

    Route::middleware('CheckRole:jobseeker')->prefix('jobseeker')->name('jobseeker.')->group(function () {
        Route::get('/dashboard', [JobSeekerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [JobSeekerController::class, 'profile'])->name('profile.index');
        Route::post('/profile', [JobSeekerController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/resume', [JobSeekerController::class, 'uploadResume'])->name('profile.resume');

        Route::post('/education', [JobSeekerController::class, 'addEducation'])->name('education.store');
        Route::put('/education/{id}', [JobSeekerController::class, 'updateEducation'])->name('education.update');
        Route::delete('/education/{id}', [JobSeekerController::class, 'deleteEducation'])->name('education.destroy');

        Route::post('/experience', [JobSeekerController::class, 'addExperience'])->name('experience.store');
        Route::put('/experience/{id}', [JobSeekerController::class, 'updateExperience'])->name('experience.update');
        Route::delete('/experience/{id}', [JobSeekerController::class, 'deleteExperience'])->name('experience.destroy');

        Route::get('/applications', [JobSeekerController::class, 'myApplications'])->name('applications.index');
        Route::get('/applications/{application}', [JobSeekerController::class, 'showApplication'])->name('applications.show');
        Route::post('/applications/{application}/withdraw', [ApplicationController::class, 'withdraw'])->name('applications.withdraw');

        Route::get('/saved-jobs', [JobSeekerController::class, 'savedJobs'])->name('saved-jobs.index');
        Route::get('/notifications', [JobSeekerController::class, 'notifications'])->name('notifications.index');
        Route::get('/notifications/{id}', [JobSeekerController::class, 'markNotificationRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [JobSeekerController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    });

    Route::middleware('CheckRole:recruiter')->prefix('recruiter')->name('recruiter.')->group(function () {
        Route::get('/dashboard', [RecruiterController::class, 'dashboard'])->name('dashboard');

        Route::get('/company/profile', [RecruiterController::class, 'companyProfile'])->name('company.profile');
        Route::post('/company/profile', [RecruiterController::class, 'updateCompany'])->name('company.update');

        Route::get('/jobs', [RecruiterController::class, 'jobs'])->name('jobs.index');
        Route::get('/jobs/create', [RecruiterController::class, 'createJob'])->name('jobs.create');
        Route::post('/jobs', [RecruiterController::class, 'storeJob'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [RecruiterController::class, 'editJob'])->name('jobs.edit');
        Route::put('/jobs/{job}', [RecruiterController::class, 'updateJob'])->name('jobs.update');
        Route::delete('/jobs/{job}', [RecruiterController::class, 'destroyJob'])->name('jobs.destroy');

        Route::get('/applications', [RecruiterController::class, 'applications'])->name('applications.index');
        Route::get('/applications/{application}', [RecruiterController::class, 'viewApplication'])->name('applications.show');
        Route::post('/applications/{application}/status', [RecruiterController::class, 'updateApplicationStatus'])->name('applications.status');
        Route::get('/applications/{application}/resume', [RecruiterController::class, 'downloadResume'])->name('applications.resume');

        Route::get('/notifications', [RecruiterController::class, 'notifications'])->name('notifications.index');
    });

    Route::middleware('CheckRole:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');

        Route::get('/companies', [AdminController::class, 'companies'])->name('companies.index');
        Route::post('/companies/{company}/approve', [AdminController::class, 'approveCompany'])->name('companies.approve');
        Route::post('/companies/{company}/reject', [AdminController::class, 'rejectCompany'])->name('companies.reject');
        Route::post('/companies/{company}/verify', [AdminController::class, 'verifyCompany'])->name('companies.verify');
        Route::post('/companies/{company}/unverify', [AdminController::class, 'unverifyCompany'])->name('companies.unverify');

        Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs.index');
        Route::get('/jobs/{job}', [AdminController::class, 'showJob'])->name('jobs.show');
        Route::post('/jobs/{job}/status', [AdminController::class, 'updateJobStatus'])->name('jobs.status');
        Route::delete('/jobs/{job}', [AdminController::class, 'destroyJob'])->name('jobs.destroy');

        Route::get('/applications', [AdminController::class, 'applications'])->name('applications.index');

        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
});
