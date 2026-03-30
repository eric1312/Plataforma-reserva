<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableDate extends Model
{
    use HasFactory;

    protected $table = 'UnavailableDates';

    public $timestamps = false; // Deshabilitar timestamps automáticos

    protected $fillable = [
        'user_id',
        'specific_date',
        'reason'
    ];

    protected $casts = [
        'specific_date' => 'date'
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
