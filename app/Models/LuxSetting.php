<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LuxSetting extends Model
{
    use HasFactory;

    protected $table = 'lux_settings';

    protected $fillable = ['warnLower', 'warnUpper', 'status', 'set_by', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
