<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoistureReading extends Model
{
    use HasFactory;

    protected $fillable = ['moisture', 'status', 'recorded_at'];

    public $timestamps = false;

    protected $dates = ['recorded_at'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
