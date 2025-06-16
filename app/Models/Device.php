<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'serial_number', 'ssid', 'password', 'is_active'];

    public function moistureReadings()
    {
        return $this->hasMany(MoistureReading::class);
    }

    public function humidityReadings()
    {
        return $this->hasMany(HumidityReading::class);
    }

    public function luxReadings()
    {
        return $this->hasMany(LuxReading::class);
    }

    public function PhReadings()
    {
        return $this->hasMany(PhReading::class);
    }

    public function WaterLevelReading()
    {
        return $this->hasMany(WaterLevelReading::class);
    }

    public function FertilizerLevelReading()
    {
        return $this->hasMany(FertilizerLevelReading::class);
    }
}
