<?php

namespace App\Models;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RainDropReading extends Model
{
    use HasFactory;

    protected $table = 'rainDrops_readings';

    protected $fillable = ['rainDrops', 'status', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
