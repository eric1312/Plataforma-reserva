<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
	use HasFactory;

	protected $table = 'Bookings'; // Apuntar a la tabla Bookings

	public $timestamps = false; // Deshabilitar timestamps

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

	// Mapeo de campos para compatibilidad
	protected $appends = ['fecha', 'hora', 'estado', 'detalle'];

	public function getFechaAttribute()
	{
		return $this->booking_date;
	}

	public function getHoraAttribute()
	{
		return $this->start_time;
	}

	public function getEstadoAttribute()
	{
		$estadoMap = [
			'confirmed' => 'confirmada',
			'pending' => 'pendiente',
			'cancelled' => 'cancelada'
		];

		return $estadoMap[$this->status] ?? $this->status;
	}

	public function getDetalleAttribute()
	{
		return $this->notes;
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class, 'service_id');
	}

	// Para compatibilidad con código existente
	public function sede()
	{
		// Retornar null o mapear a service si es necesario
		return null;
	}
}
