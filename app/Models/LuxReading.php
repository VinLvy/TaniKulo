<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LuxReading extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'value'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
