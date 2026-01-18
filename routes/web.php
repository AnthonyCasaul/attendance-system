<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Attendance Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update-picture');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
});

// Admin Routes (Protected and Admin Only)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/attendances', [AdminController::class, 'userAttendances'])->name('user.attendances');
    Route::get('/attendances', [AdminController::class, 'attendances'])->name('attendances');
    Route::get('/attendances/{attendance}', [AdminController::class, 'attendanceDetail'])->name('attendance.detail');
    Route::get('/attendances/{attendance}/edit', [AdminController::class, 'editAttendance'])->name('attendance.edit');
    Route::put('/attendances/{attendance}', [AdminController::class, 'updateAttendance'])->name('attendance.update');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
