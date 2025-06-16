<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FertilizerLevelReading extends Model
{
    protected $table = 'fertilizer_logs';

    protected $fillable = ['status', 'value', 'recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
