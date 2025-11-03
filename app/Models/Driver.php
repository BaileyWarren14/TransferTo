<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Document;

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
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
     public function documents()
    {
        return $this->hasMany(Document::class, 'driver_id');
    }
}
