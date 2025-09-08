<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Inspection extends Model
{
    //
   use HasFactory;

    protected $fillable = [
        'driver_id', 'truck_id', 'trailer_id',
        'pre_trip', 'post_trip', 'truck_number', 'odometer', 'unit',
        'condition', 'remarks', 'signature', 'inspection_date', 'inspection_time',
        // Checklist fields
        'air_compressor','air_lines','axles','battery','belts','body_frame','brakes_adjustment','brakes_service',
        'brakes_parking','charging_system','clutch','cooling_system','coupling_devices','documents','doors',
        'drive_lines','emergency_equipment','emergency_windows','engine','exhaust_system','fire_extinguishers',
        'first_aid','fluid_leaks','frame','fuel_system','heater','horns','inspection_decals','interior_ligths',
        'lights_reflectors','load_security_device','lubrication_system','mirrows','mud_flaps','oil_pressure',
        'rear_end','recording_devices','seats','suspension','steering_mechanism','transmission','wheels_tires',
        'windows','wipers','other','trailer1','trailer2'
    ];

    // Relación con Driver
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Relación con Truck
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    // Relación con Trailer
    public function trailer()
    {
        return $this->belongsTo(Trailer::class);
    }
}
