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

Route::get('/driver/log_book', function () {
    return view('driver.log_book'); // new.blade.php
})->name('log_book');

Route::get('trip', function () {
    return view('trip_inspection');
});
Route::get('triped', function () {
    return view('new');
});
Route::get('menu', function () {
    return view('menu');
})->name('menu');
Route::get('/driver/new', function () {
    return view('driver.inspections.new'); // new.blade.php
})->name('new');

Route::get('/driver/details', function () {
    return view('driver.details.details'); // details.blade.php
})->name('details');

//para mostrar la lista de logs



//Route::view('/menu', 'menu')->name('menu');
//Para el registro de usuarios al registrarse correctamente
Route::get('/register/success/{user_id}', [RegisterController::class, 'registerSuccess'])->name('register.success');


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


// Alias para que Laravel no truene con middleware auth
Route::get('/login', function () {
    return redirect()->route('log');
})->name('login');
// Para las peticiones de la inspeccion
//Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
Route::middleware(['auth:driver'])->group(function () {
    Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
    Route::post('/inspections/store', [InspectionController::class, 'store'])->name('inspections.store');
    //para listar las inspecciones
    Route::get('/driver/list', [InspectionController::class, 'index'])->name('driver.inspections');
    //editar inspecciones
    Route::get('/driver/inspections/{id}/edit', [InspectionController::class, 'edit'])
        ->name('driver.inspections.edit_inspection');

    Route::put('/inspections/{id}', [InspectionController::class, 'edit'])->name('driver.inspections.edit');

    // Ruta para mostrar el formulario de edición
    Route::get('/driver/inspections/{id}/edit', [InspectionController::class, 'edit'])
        ->name('driver.inspections.edit_inspection');

    // Ruta para procesar la actualización (PUT)
    Route::put('/driver/inspections/{id}', [InspectionController::class, 'update'])
        ->name('driver.inspections.update');
    // Ruta para listar inspecciones (la que falta)
    Route::get('/driver/inspections', [InspectionController::class, 'index'])
    ->name('driver.inspections.list_inspection');

    


    //Para generar el pdf
    Route::get('/inspections/pdf/{id}', [InspectionController::class, 'generatePDF'])->name('inspections.pdf');
});



//Auth::routes();

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
    Route::get('/driver/dashboard', function () {
        return view('/driver/dashboard');
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

