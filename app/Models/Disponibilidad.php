<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
	use HasFactory;

	protected $table = 'Availability'; // Apuntar a la tabla Availability

	public $timestamps = false; // Deshabilitar timestamps

	protected $fillable = [
		'user_id',
		'day_of_week',
		'start_time',
		'end_time',
		'is_available'
	];

	// Mapeo de campos para compatibilidad
	protected $appends = ['fecha', 'hora_inicio', 'hora_fin'];

	public function getFechaAttribute()
	{
		// Para compatibilidad, retornar null o calcular fecha actual
		return now()->format('Y-m-d');
	}

	public function getHoraInicioAttribute()
	{
		return $this->start_time;
	}

	public function getHoraFinAttribute()
	{
		return $this->end_time;
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	// Para compatibilidad con código existente
	public function sede()
	{
		// Retornar null o mapear a user si es necesario
		return null;
	}
}
