<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterLog extends Model
{
    protected $fillable = ['device_id', 'status', 'amount', 'recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
