<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterLevelReading extends Model
{
    protected $table = 'water_logs';

    protected $fillable = ['status', 'value', 'recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
