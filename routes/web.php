<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AuthController;
Route::get('/', function () {
    return view('welcome');
});

Route::name('students.')->prefix('students')->group(function () {

    Route::get('/', [StudentController::class, 'index'])->name('index');

    Route::get('/{id}', [StudentController::class, 'show'])->name('show')->whereNumber('id');

    Route::get('/create', [StudentController::class, 'create'])->name('create');

    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');

    Route::post('/', [StudentController::class, 'store'])->name('store');

    Route::put('/{id}', [StudentController::class, 'update'])->name('update');

    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
}); 

Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
Route::get('/student/cash-report', [StudentController::class, 'cashReport'])->name('student.cash-report');
Route::get('/student/waste-deposit', [StudentController::class, 'wasteDeposit'])->name('student.deposit');
Route::get('/student/cash-withdrawal', [StudentController::class, 'cashWithdrawal'])->name('student.cash-withdrawal');

Route::name('teachers.')->prefix('teachers')->group(function () {

    Route::get('/', [TeacherController::class, 'index'])->name('index');

    Route::get('/waste-deposit', [TeacherController::class, 'wasteDeposit'])->name('waste-deposit');

    Route::post('/waste-deposit', [TeacherController::class, 'storeWasteDeposit'])->name('waste-deposit.store');

    Route::get('/cash-report', [TeacherController::class, 'cashReport'])->name('cash-report');

    Route::put('/cash-report/{id}/approve', [TeacherController::class, 'approveWithdrawal'])->name('cash-report.approve');

    Route::delete('/cash-report/{id}/reject', [TeacherController::class, 'rejectWithdrawal'])->name('cash-report.reject');

    Route::get('/{id}', [TeacherController::class, 'show'])->name('show')->whereNumber('id');

    Route::get('/create', [TeacherController::class, 'create'])->name('create');

    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('edit');

    Route::post('/', [TeacherController::class, 'store'])->name('store');

    Route::put('/{id}', [TeacherController::class, 'update'])->name('update');

    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

Route::name('auth.')->prefix('auth')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    Route::get('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/register', [AuthController::class, 'store'])->name('store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

