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
        'conditions', 'remarks', 'signature', 'inspection_date', 'inspection_time',
        'checklist','trailer1','trailer2'
    ];
     protected $casts = [
        'pre_trip' => 'boolean',
        'post_trip' => 'boolean',
        'checklist' => 'array', // 🔹 Laravel convierte JSON en array automáticamente
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
