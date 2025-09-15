<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dutystatuslog extends Model
{
    //
    protected $table = 'duty_status_logs';

    protected $fillable = [
        'driver_id',
        'status',
        'location',
        'notes',
        'changed_at',
    ];

    // Relación con Driver
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public $timestamps = true; // created_at y updated_at
}
