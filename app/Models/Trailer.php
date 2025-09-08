<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
     protected $table = 'trailers';

    protected $fillable = [
        'axles',
        'trailer_type',
        'license_plate'
    ];
}
