<?php

namespace App\Models;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FertilizerLevelSetting extends Model
{
    use HasFactory;

    protected $table = 'fertilizer_settings';

    protected $fillable = ['warnLower', 'warnUpper', 'status', 'set_by', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
