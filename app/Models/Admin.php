<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
     use Notifiable;

    protected $table = 'administrators';

    protected $fillable = [
        'name',
        'lastname',
        'phone_number',
        'email',
        'password',
        'department',
        'position',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
