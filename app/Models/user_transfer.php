<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_transfer extends Model
{
    protected $table = 'users_transfer';

    protected $fillable =[
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function getAuthPassword(){
        return $this -> password;
    }

    public $timestamps = false;
}
