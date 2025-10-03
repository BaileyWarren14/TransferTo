<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverAlert extends Model
{
    //
    use HasFactory;

    protected $fillable = ['driver_id', 'type', 'message', 'alerted_at'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
