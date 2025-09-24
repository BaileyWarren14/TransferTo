<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InspectionController;
//use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\DutyStatusController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\TimezoneController;
use App\Http\Controllers\DriverController;


Route::get('/', function () {
    return view('welcome');
});


// Login de drivers
Route::get('/log', [LogController::class, 'showLoginForm'])->name('log');
Route::post('/log', [LogController::class, 'login'])->name('login.post');
Route::post('/logout', [LogController::class, 'logout'])->name('logout');

// Login de admins
//Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
//Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
//Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


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
    Route::get('/driver/inspections/pdf/{id}', [InspectionController::class, 'generatePDF'])->name('driver.inspections.pdf');

    Route::get('/driver/log_book', function () {
        return view('driver.logs.log_book'); // new.blade.php
    })->name('driver.logs.log_book');

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

    //para los cambios de estado 
    // Mostrar el formulario
    Route::get('/driver/change_duty_status', [DutyStatusController::class, 'create'])
        ->name('driver.duty_status.changeDutyStatus.create');

    // Guardar los datos
    Route::post('/driver/change_duty_status', [DutyStatusController::class, 'store'])
        ->name('driver.duty_status.changeDutyStatus.store');


    Route::get('/dashboard', [DeviceController::class, 'index'])->name('dashboard');

    // Mostrar formulario de registro
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

    // Procesar envío del formulario
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');


    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');

    //Para que se hagan y guarden lso cambios del duty
    Route::get('/change_duty_status', [DutyStatusController::class, 'create'])->name('driver.duty_status.create');
    Route::post('/change_duty_status', [DutyStatusController::class, 'store'])->name('driver.duty_status.store');

    
    //Ruta para obtener el odometer del truck mediante el plate
    Route::get('/trucks/find/{plate}', [TruckController::class, 'findByPlate'])
        ->name('trucks.find');

    //Ruta para mostrar los cambios de estado en el dia
    Route::get('today', [LogbookController::class, 'today'])->name('driver.logs.today');
    //ruta para ver el libro electronico
    Route::get('/driver/show', [LogbookController::class, 'index'])->name('driver.logs.show');

    //Ruta para entrar a ver la informacion del truck actual
    Route::get('/driver/about', [TruckController::class, 'about'])->name('driver.about_truck.about_truck');


});



//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');






// Dashboard para drivers
// Dashboard para drivers (protegido por guard 'driver')
Route::middleware(['auth:driver'])->group(function () {
    Route::get('/driver/dashboard', function () {
        return view('/driver/dashboard');
    })->name('driver.dashboard');
    
});


// Dashboard para admins (protegido por guard 'admin')
Route::middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Trucks
    Route::get('/admin/trucks', [TruckController::class, 'index'])->name('admin.trucks');

    // Trailers
    Route::get('/admin/trailers', [TrailerController::class, 'index'])->name('admin.trailers');

    // Drivers
    Route::get('/admin/drivers', [DriverController::class, 'index'])->name('admin.drivers');

    // Crear admin
    Route::get('/admin/drivers/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/drivers/store', [AdminController::class, 'store'])->name('admin.store');

    // CRUD de drivers (admin maneja a los drivers)
    Route::get('/admin/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/admin/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/admin/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');
});



//para el log out de usuarios
Route::post('/logout', [LogController::class, 'logout'])->name('logout');



//para crear administradores de forma temporal


   


    // Dashboard
    //Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    

    // Details
    //Route::get('/details', [AdminDashboardController::class, 'details'])->name('admin.details');




//Para poder automatizar el registro del log a las 00:00
//Route::get('/logbook/{logbook}', [LogbookController::class, 'show'])->name('logbook.show');

// Mostrar Logbook diario
//Route::get('/driver/logs/today', [LogbookController::class, 'today'])
  //  ->name('driver.logs.today')
    //->middleware('auth:driver');

    // Guardar estado (ya existe)
Route::prefix('driver/logs')->middleware('auth:driver')->group(function () {
    
});;


//Route::get('/logbook/today', [LogbookController::class, 'today'])->name('logbook.today');