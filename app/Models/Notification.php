<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = ['device_id', 'title', 'message', 'is_read'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // TESTING
    // protected $fillable = [
    //     'title',
    //     'message',
    // ];

    // /**
    //  * Get the notification's title.
    //  */
    // public function getTitleAttribute($value)
    // {
    //     return ucfirst($value);
    // }

    // /**
    //  * Get the notification's message.
    //  */
    // public function getMessageAttribute($value)
    // {
    //     return ucfirst($value);
    // }
}
