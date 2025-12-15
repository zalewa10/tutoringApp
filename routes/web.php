<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LessonController; // added
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HistoryController;

Route::get('/', function () {
    return redirect()->route('show.login');
});


// Strony logowania i rejestrowania
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');

// Obsługa formularzy logowania i rejestrowania
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->controller(StudentController::class)->group(function () {
    // Przekierowania
    Route::get('/dashboard', 'index')->name('dashboard.index');
    Route::get('dashboard/create', 'create')->name('dashboard.create');
    Route::get('/dashboard/{id}', 'show')->name('dashboard.show');
    Route::get('/dashboard/{id}/edit', 'edit')->name('dashboard.edit');
    Route::put('/dashboard/{id}', 'update')->name('dashboard.update');
    Route::post('/dashboard', 'store')->name('dashboard.store');
    Route::delete('/dashboard/{id}', 'delete')->name('dashboard.delete');

    //listing uczniów
    Route::get('/students', [StudentsController::class, 'index'])->name('students.index');

    // lessons
    Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');
    Route::get('/lessons/{id}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{id}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{id}', [LessonController::class, 'destroy'])->name('lessons.destroy');

    // AJAX endpoints for calendar interactions
    Route::post('/lessons/ajax', [LessonController::class, 'ajaxStore'])->name('lessons.ajaxStore');
    Route::put('/lessons/{id}/ajax', [LessonController::class, 'ajaxUpdate'])->name('lessons.ajaxUpdate');
    Route::delete('/lessons/{id}/ajax', [LessonController::class, 'ajaxDestroy'])->name('lessons.ajaxDestroy');

    // payments
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/{id}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.markPaid');
    Route::post('/payments/bulk-mark-paid', [PaymentController::class, 'bulkMarkPaid'])->name('payments.bulkMarkPaid');
    Route::put('/payments/{id}/status', [PaymentController::class, 'updateStatus'])->name('payments.updateStatus');

    // history (combined with finance)
    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history.index')
        ->middleware('auth');
});
