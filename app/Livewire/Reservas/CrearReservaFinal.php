<?php

namespace App\Livewire\Reservas;

use Livewire\Component;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class CrearReservaFinal extends Component
{
    public $fechaSeleccionada;
    public $horaSeleccionada;
    public $detalle;

    public $fechasDisponibles = [];
    public $horasDisponibles = [];

    protected $rules = [
        'fechaSeleccionada' => 'required|date|after_or_equal:today',
        'horaSeleccionada' => 'required',
        'detalle' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        // Generar fechas disponibles para los próximos 30 días
        $this->fechasDisponibles = [];
        $startDate = now();
        for ($i = 0; $i < 30; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $this->fechasDisponibles[] = $currentDate->format('Y-m-d');
        }

        // Horarios fijos
        $this->horasDisponibles = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
            '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'
        ];
    }

    public function reservar()
    {
        $this->validate();

        try {
            // Forzar hora si no está seleccionada
            if (!$this->horaSeleccionada && !empty($this->horasDisponibles)) {
                $this->horaSeleccionada = $this->horasDisponibles[0];
            }

            $reserva = Reserva::create([
                'user_id' => Auth::id(),
                'service_id' => 1,
                'booking_date' => $this->fechaSeleccionada,
                'start_time' => $this->horaSeleccionada,
                'end_time' => date('H:i:s', strtotime($this->horaSeleccionada) + 3600),
                'status' => 'pending',
                'notes' => $this->detalle,
                'created_at' => now()
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('reservas.create')->with('success', 
                "¡Reserva creada exitosamente! ID: {$reserva->id} para el {$this->fechaSeleccionada} a las {$this->horaSeleccionada}"
            );

        } catch (\Exception $e) {
            // Redirigir con mensaje de error
            return redirect()->route('reservas.create')->with('error', 
                'Error al crear la reserva: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.reservas.crear-reserva-final');
    }
}
