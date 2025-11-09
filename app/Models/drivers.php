<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;

class drivers extends Model
{
    //
    

    protected $table = 'drivers'; // tabla
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
protected $hidden = ['password'];
 public function documents()
    {
        return $this->hasMany(Document::class, 'driver_id');
    }
    public function truck()
{
    return $this->hasOne(Truck::class, 'driver_id');
}
}
