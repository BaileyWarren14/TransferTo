<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class drivers extends Model
{
    //
    

    protected $table = 'drivers'; // tu tabla
    protected $fillable = [
    'name',
    'lastname',
    'phone_number',
    'email',
    'social_security_number',
    'password',
    'license_number'
];
protected $hidden = ['password'];
}
