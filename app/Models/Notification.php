<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'read_at',
    ];

    public function user()
    {
        return $this->belongsTo(Driver::class); // si la notificación es solo para drivers
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }
}
