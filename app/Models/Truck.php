<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $table = 'trucks';

    protected $fillable = [
        'license_plate',
        'brand',
        'model',
        'year',
        'current_mileage',
        'fuel_capacity',
        'color',
        'cab_type',
        'transmission_type',
        'status',
        'current_motor_hours',
        'driver_id'
    ];
}
