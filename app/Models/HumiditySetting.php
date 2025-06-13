<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HumiditySetting extends Model
{
    use HasFactory;

    protected $table = 'humidity_settings';

    protected $fillable = ['device_id', 'warnLower', 'warnUpper', 'status', 'set_by', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
