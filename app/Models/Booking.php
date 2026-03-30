<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'Bookings';

    public $timestamps = false; // Deshabilitar timestamps automáticos

    protected $fillable = [
        'user_id',
        'service_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'created_at'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'created_at' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function professional()
    {
        return $this->hasOneThrough(
            User::class,
            Service::class,
            'id',
            'id',
            'service_id',
            'user_id'
        );
    }
}
