<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Document;
use App\Models\Fuel;
use App\Models\Notification;

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
    public function truck()
    {
        return $this->hasOne(Truck::class, 'driver_id');
    }
    public function fuels()
    {
        return $this->hasMany(Fuel::class, 'driver_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

}
