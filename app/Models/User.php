<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang bisa diisi (mass assignable)
     */
    protected $fillable = ['username', 'email', 'password', 'role', 'role_id'];

    /**
     * Atribut yang harus disembunyikan saat serialisasi
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Atribut yang harus dikonversi ke tipe data lain
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relasi dengan tabel orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

}
