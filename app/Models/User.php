<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // ← Tambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ← Tambahkan HasApiTokens di sini

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id', // ← Jangan lupa tambahkan jika belum
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
