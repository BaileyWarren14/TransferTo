<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspectionController;
//use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RegisterController;

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
})->name('menu');
Route::get('/new', function () {
    return view('new'); // new.blade.php
})->name('new');

Route::get('/details', function () {
    return view('details'); // details.blade.php
})->name('details');

//Route::view('/menu', 'menu')->name('menu');

Route::get('/log', [LogController::class, 'showLoginForm'])->name('login');

Route::post('/log', [LogController::class, 'login'])->name('login.post');


Route::post('/fuel/store', [FuelController::class, 'store'])->name('fuel.store');



// routes/web.php
Route::post('/inspection/save', [InspectionController::class, 'save'])->name('Inspection.save');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/dashboard', [DeviceController::class, 'index'])->name('dashboard');

// Mostrar formulario de registro
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

// Procesar envío del formulario
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');