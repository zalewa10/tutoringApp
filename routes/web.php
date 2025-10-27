<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect()->route('show.login');
});


// Strony logowania i rejestrowania
Route::get('/register', [AuthController::class,'showRegister'])->name('show.register');
Route::get('/login', [AuthController::class,'showLogin'])->name('show.login');

// Obsługa formularzy logowania i rejestrowania
Route::post('/register', [AuthController::class,'register'])->name('register');
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->controller(StudentController::class)->group(function(){
    // Przekierowania
    Route::get('/dashboard', 'index')->name('dashboard.index');
    Route::get('dashboard/create', 'create')->name('dashboard.create');
    Route::get('/dashboard/{id}', 'show')->name('dashboard.show');
    Route::post('/dashboard', 'store')->name('dashboard.store');
    Route::delete('/dashboard/{id}', 'delete')->name('dashboard.delete');
});