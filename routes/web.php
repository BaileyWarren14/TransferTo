<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('trip', function () {
    return view('trip_inspection');
});
Route::get('triped', function () {
    return view('new');
});
Route::get('menu', function () {
    return view('menu');
});
Route::get('/new', function () {
    return view('new'); // new.blade.php
})->name('new');

Route::get('/details', function () {
    return view('details'); // details.blade.php
})->name('details');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
