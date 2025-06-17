<?php

namespace App\Models;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WaterLevelSetting extends Model
{
    use HasFactory;

    protected $table = 'water_setting';

    protected $fillable = ['device_id', 'warnLower', 'warnUpper', 'status', 'set_by', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
