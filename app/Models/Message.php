<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'sender_id', 
        'sender_type',
        'receiver_id', 
        'receiver_type', 
        'message',
        'created_at', 
        'updated_at', 
    ];
    public $timestamps = false;

    /**
     * Obtiene el nombre del remitente, buscando en Driver o Administrator
     */
    public function senderName()
    {
        $driver = Driver::find($this->sender_id);
        if ($driver) return $driver->name;

        $admin = Admin::find($this->sender_id);
        if ($admin) return $admin->name;

        return 'Unknown';
    }

    /**
     * Obtiene el nombre del receptor, buscando en Driver o Administrator
     */
    public function receiverName()
    {
        $driver = Driver::find($this->receiver_id);
        if ($driver) return $driver->name;

        $admin = Admin::find($this->receiver_id);
        if ($admin) return $admin->name;

        return 'Unknown';
    }

    public function isSentByAuth()
    {
        return $this->sender_id == auth()->id();
    }
}
