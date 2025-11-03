<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Document extends Model
{
    //
     protected $fillable = ['type', 'file_name', 'file_path',  'driver_id',];
      public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
