<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'Users';

    public $timestamps = false; // Deshabilitar timestamps automáticos

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'created_at'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProfessional()
    {
        return $this->role === 'professional';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function availability()
    {
        return $this->hasMany(Availability::class, 'user_id');
    }

    public function unavailableDates()
    {
        return $this->hasMany(UnavailableDate::class, 'user_id');
    }
}
