<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('hello');
});

Route::get('/dashboard', function (){
    return view('dashboard.index');
});