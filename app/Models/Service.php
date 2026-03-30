<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'Services';

    public $timestamps = false; // Deshabilitar timestamps automáticos

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'price',
        'user_id',
        'created_at'
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
