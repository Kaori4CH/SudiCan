<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->canAccessTeacherArea()) {
            return redirect()->route('teachers.index');
        }

        return redirect()->route('student.dashboard');
    }

    return redirect()->route('auth.login');
});

Route::get('/student', function () {
    if (Auth::check()) {
        return redirect()->route('student.dashboard');
    }

    return redirect()->route('auth.login');
});
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth'])->name('student.')->prefix('student')->group(function () {
    Route::get('/select-class', [StudentController::class, 'selectClass'])->name('select-class');
    Route::post('/select-class', [StudentController::class, 'storeClassSelection'])->name('select-class.store');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/cash-report', [StudentController::class, 'cashReport'])->name('cash-report');
    Route::get('/waste-deposit', [StudentController::class, 'wasteDeposit'])->name('deposit');
    Route::get('/cash-withdrawal', [StudentController::class, 'cashWithdrawal'])->name('cash-withdrawal');
    Route::post('/cash-withdrawal', [StudentController::class, 'storeCashWithdrawal'])->name('cash-withdrawal.store');
    Route::get('/greenrewards', [StudentController::class, 'greenRewards'])->name('greenrewards');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::name('teachers.')->prefix('teachers')->middleware(['teacher'])->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::get('/waste-deposit', [TeacherController::class, 'wasteDeposit'])->name('waste-deposit');
    Route::post('/waste-deposit', [TeacherController::class, 'storeWasteDeposit'])->name('waste-deposit.store');
    Route::get('/cash-report', [TeacherController::class, 'cashReport'])->name('cash-report');
    Route::put('/cash-report/{id}/approve', [TeacherController::class, 'approveWithdrawal'])->name('cash-report.approve')->whereNumber('id');
    Route::delete('/cash-report/{id}/reject', [TeacherController::class, 'rejectWithdrawal'])->name('cash-report.reject')->whereNumber('id');
    Route::get('/waste-prices', [TeacherController::class, 'wastePrices'])->name('waste-prices.index');
    Route::get('/waste-prices/create', [TeacherController::class, 'wastePricesCreate'])->name('waste-prices.create');
    Route::post('/waste-prices', [TeacherController::class, 'wastePricesStore'])->name('waste-prices.store');
    Route::get('/waste-prices/{id}/edit', [TeacherController::class, 'wastePricesEdit'])->name('waste-prices.edit')->whereNumber('id');
    Route::put('/waste-prices/{id}', [TeacherController::class, 'wastePricesUpdate'])->name('waste-prices.update')->whereNumber('id');
    Route::delete('/waste-prices/{id}', [TeacherController::class, 'wastePricesDestroy'])->name('waste-prices.destroy')->whereNumber('id');
    Route::get('/accounts', [TeacherController::class, 'accounts'])->name('accounts');
    Route::get('/create', [TeacherController::class, 'create'])->name('create');
    Route::post('/', [TeacherController::class, 'store'])->name('store');
    Route::get('/{id}', [TeacherController::class, 'show'])->name('show')->whereNumber('id');
    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('edit')->whereNumber('id');
    Route::put('/{id}', [TeacherController::class, 'update'])->name('update')->whereNumber('id');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy')->whereNumber('id');
});

Route::name('auth.')->prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('store');
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
});
