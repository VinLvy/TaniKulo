<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HumidityReading extends Model
{
    use HasFactory;

    protected $table = 'humidity_readings';

    protected $fillable = ['device_id', 'humidity', 'temperature', 'status', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
