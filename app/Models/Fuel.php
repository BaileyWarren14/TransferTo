<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fuel extends Model
{
    //
     use HasFactory;

    protected $table = 'fuel_log_form';

    protected $fillable = [
        'date', 'bol_number', 'trailer', 'from', 'destination',
        'iso_capacity', 'inches_gallon', 'mileage_before', 'mileage_after',
        'total_miles', 'fuel_dispensed', 'efficiency', 'truck_id', 'bol_path'
    ];

    
    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id'); // especificar llave foránea
    }

}
