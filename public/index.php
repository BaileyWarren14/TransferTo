<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

//yHFs5dg44bVhXX


// We're setting up your account! The username if0_40596137 has been assigned to it. Here are some things you need to know:

//     It will take a few minutes for your account to be set up.
//     It can take up to 72 hours for the new domain to be visible everywhere, due to DNS caching.
//     Please login to the control panel once to enable all features.
//     Not sure what to do next? Please see this guide for some ideas on how to get started.

