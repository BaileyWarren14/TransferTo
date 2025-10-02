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
use App\Http\Controllers\TrailerController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;



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
    
    //para consultar el estado mas reciente en los cambios de estado y poder detener iniciar los cronometros
    Route::get('/driver/logs/latest', [LogbookController::class, 'latest'])->name('driver.logs.latest');

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

    //Route::get('/driver/details', function () {
      //  return view('driver.details.details'); // details.blade.php
    //})->name('details');

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
    //para que obtenga los datos y se actualice el logbook automatico
    //Route::get('/driver/logs/today-data', [LogbookController::class, 'todayData'])->name('driver.logs.today-data');

    //Ruta para ver los logs del dia actual completos
    Route::get('/driver/activities/{date}', [LogbookController::class, 'showActivities'])
     ->name('driver.logs.activities');

    //Ruta para editar un cambio de estado desde el resumen de logs del dia
    Route::get('driver/duty_status/{log}/edit', [DutyStatusController::class, 'edit'])->name('driver.duty_status.edit');

     
    // Actualizar Duty Status
    Route::put('/duty_status/{log}', [DutyStatusController::class, 'update'])
        ->name('driver.duty_status.update');

    //Ruta para mostrar el menu de las workorder
    Route::get('/driver/menu', [WorkOrderController::class, 'index'])->name('workorder.index');

    Route::get('/driver/cisterns', [WorkOrderController::class, 'cisterns'])->name('workorder.cisterns');
    Route::get('/driver/dry-box', [WorkOrderController::class, 'dryBox'])->name('workorder.drybox');
    Route::get('/driver/platform', [WorkOrderController::class, 'platform'])->name('workorder.platform');
    Route::get('/driver/pneumatic', [WorkOrderController::class, 'pneumatic'])->name('workorder.pneumatic');

    //Ruta para entrar a ver la informacion del truck actual
    Route::get('/driver/about', [TruckController::class, 'about'])->name('driver.about_truck.about_truck');

    // Lista de usuarios disponibles para chat
    //Route::get('/driver/messages', [DriverController::class, 'messages'])->name('driver.messages');

   // Mostrar todos los drivers y admins
    Route::get('driver/messages', [MessageController::class, 'index'])->name('messages.index');

    // Mostrar chat con usuario seleccionado
    Route::get('/messages/{type}/{id}', [MessageController::class, 'chat'])->name('messages.chat');

    // Enviar mensaje
    Route::post('/messages/{type}/{id}', [MessageController::class, 'send'])->name('messages.send');

    // Mensajes en JSON (para refresco automático)
    Route::get('/messages/{type}/{id}/json', [MessageController::class, 'messagesJson'])->name('messages.json');


    //Ruta para ver la vista de safety
    Route::get('/driver/safety', [DriverController::class, 'safety'])->name('driver.safety');

    // Mostrar vista de notifications
    Route::get('driver/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Marcar notificación como leída
    Route::post('driver/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');

    // Traer notificaciones en JSON (para actualización automática o AJAX)
    Route::get('driver/notifications/json', [NotificationController::class, 'json'])->name('notifications.json');

    //Para eliminar una notificacion de la vista
    Route::post('driver/notifications/{id}/markRead', [NotificationController::class, 'markRead'])->name('notifications.markRead');


    //Ruta para ver la vista de documents
    Route::get('/driver/documents', [DriverController::class, 'documents'])->name('driver.documents');
    
    //Ruta Para recibir y guardar la zona horaria y utilizarla en toda la aplicacion
    Route::post('/set-timezone', function (Request $request) {
    $tz = $request->timezone ?? 'UTC';
    session(['user_timezone' => $tz]);
    return response()->json(['status' => 'ok', 'timezone' => $tz]);
})->name('set.timezone');
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

    // Drivers
    Route::get('/admin/drivers', [DriverController::class, 'index'])->name('admin.drivers');

  
    // CRUD de drivers (admin maneja a los drivers)    
    // List all drivers
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');

    // Create a new driver
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');

    // Edit an existing driver
    Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
    Route::resource('drivers', DriverController::class);

    // Delete a driver
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.destroy');

     // Dashboard Trucks
    Route::get('/admin/trucks', [TruckController::class, 'index'])->name('trucks.list_trucks');

    // Crear Truck
    Route::get('/admin/trucks/create', [TruckController::class, 'create'])->name('trucks.create');
    Route::post('/admin/trucks', [TruckController::class, 'store'])->name('trucks.store');

    // Editar Truck
    Route::get('/admin/trucks/{truck}/edit', [TruckController::class, 'edit'])->name('trucks.edit');
    Route::put('/admin/trucks/{truck}', [TruckController::class, 'update'])->name('trucks.update');

    // Eliminar Truck
    Route::delete('/admin/trucks/{truck}', [TruckController::class, 'destroy'])->name('trucks.destroy');


  
    // Listado de trailers
    Route::get('/admin/trailers', [TrailerController::class, 'index'])->name('trailers.index');

    // Crear nuevo trailer
    Route::get('/admin/trailers/create', [TrailerController::class, 'create'])->name('trailers.create');
    Route::post('/admin/trailers', [TrailerController::class, 'store'])->name('trailers.store');

    // Editar trailer
    Route::get('/admin/trailers/{trailer}/edit', [TrailerController::class, 'edit'])->name('trailers.edit');
    Route::put('/admin/trailers/{trailer}', [TrailerController::class, 'update'])->name('trailers.update');

    // Eliminar trailer
    Route::delete('/admin/trailers/{trailer}', [TrailerController::class, 'destroy'])->name('trailers.destroy');

    // Listado de admins
    Route::get('/admin/admin', [AdminController::class, 'index'])->name('admin.index');

    // Crear nuevo admin
    Route::get('/admin/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/admin', [AdminController::class, 'store'])->name('admin.store');

    // Editar admin
    Route::get('/admin/admin/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/admin/{admin}', [AdminController::class, 'update'])->name('admin.update');

    // Eliminar admin
    Route::delete('/admin/admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // Mostrar todos los drivers y admins
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    // Mostrar chat con usuario seleccionado
    Route::get('/messages/{type}/{id}', [MessageController::class, 'chat'])->name('messages.chat');

    // Enviar mensaje
    Route::post('/messages/{type}/{id}', [MessageController::class, 'send'])->name('messages.send');

    // Mensajes en JSON (para refresco automático)
    Route::get('/messages/{type}/{id}/json', [MessageController::class, 'messagesJson'])->name('messages.json');


    // Mostrar vista de notifications
    Route::get('admin/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Marcar notificación como leída
    Route::post('admin/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');

    // Traer notificaciones en JSON (para actualización automática o AJAX)
    Route::get('admin/notifications/json', [NotificationController::class, 'json'])->name('notifications.json');

    //Para eliminar una notificacion de la vista
    Route::post('admin/notifications/{id}/markRead', [NotificationController::class, 'markRead'])->name('notifications.markRead');

    //Ruta para ver la vista de documents
    Route::get('/admin/documents', [DriverController::class, 'documents'])->name('driver.documents');
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