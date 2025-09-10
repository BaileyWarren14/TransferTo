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



// Login de drivers
Route::get('/log', [LogController::class, 'showLoginForm'])->name('log');
Route::post('/log', [LogController::class, 'login'])->name('login.post');
Route::post('/logout', [LogController::class, 'logout'])->name('logout');

// Login de admins
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


//ruta del trip inspection

Route::post('/fuel/store', [FuelController::class, 'store'])->name('fuel.store');



// Para las peticiones de la inspeccion
Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
Route::middleware(['auth:driver'])->group(function () {
    Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
    Route::post('/inspections/store', [InspectionController::class, 'store'])->name('inspections.store');
});




//Para generar el pdf
Route::get('/inspections/pdf/{id}', [InspectionController::class, 'generatePDF'])->name('inspections.pdf');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/dashboard', [DeviceController::class, 'index'])->name('dashboard');

// Mostrar formulario de registro
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

// Procesar envío del formulario
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');


// Dashboard para drivers
// Dashboard para drivers (protegido por guard 'driver')
Route::middleware(['auth:driver'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('driver.dashboard');
});

// Dashboard para admins (protegido por guard 'admin')
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

//para el log out de usuarios
Route::post('/logout', [LogController::class, 'logout'])->name('logout');

