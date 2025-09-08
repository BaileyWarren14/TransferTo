<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Driver extends Authenticatable
{
    use Notifiable;

    protected $table = 'drivers';

    protected $fillable = [
        'name',
        'lastname',
        'phone_number',
        'email',
        'social_security_number',
        'password',
        'license_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
