<?php

namespace App\Livewire\Reservas;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use App\Models\Disponibilidad;
use App\Models\Reserva;
use App\Models\Notificacion;
use App\Models\AdminNotification;
use App\Models\Sede;
use App\Mail\ConfirmacionReservaMail;
use App\Mail\NotificarAdminReservaMail;
use App\Mail\NotificacionReservaMail;
use App\Notifications\SedeSaturadaNotification;
use App\Traits\NotificacionTrait;

class CrearReserva extends Component
{
    use NotificacionTrait;

	public $fechaSeleccionada;
	public $horaSeleccionada;
	public $detalle;

    public $fechasDisponibles = [];
	public $horasDisponibles = [];
    public $fechasSaturadas = [];

	protected $rules = [
        'fechaSeleccionada' => 'required|date|after_or_equal:today',
        'horaSeleccionada' => 'required',
        'detalle' => 'nullable|string|max:255',
    ];

	protected $messages = [
        'fechaSeleccionada.required' => 'La fecha es obligatoria.',
        'fechaSeleccionada.after_or_equal' => 'La fecha debe ser hoy o posterior.',
        'horaSeleccionada.required' => 'La hora es obligatoria.',
    ];

    protected $listeners = ['fechaSeleccionadaCambiada' => 'actualizarFecha'];

    public $tituloFuncion;

    public function mount($titulo = null)
    {
        $this->tituloFuncion = $titulo;

        // Generar fechas disponibles para los próximos 30 días basados en disponibilidad
        $this->fechasDisponibles = [];
        $startDate = now();
        for ($i = 0; $i < 30; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $dayOfWeek = $currentDate->dayOfWeek == 0 ? 6 : $currentDate->dayOfWeek - 1; // Convertir a 0-6 (Lunes-Domingo)

            $hasAvailability = Disponibilidad::where('day_of_week', $dayOfWeek)
                ->where('is_available', 1)
                ->exists();

            if ($hasAvailability) {
                $this->fechasDisponibles[] = $currentDate->format('Y-m-d');
            }
        }

        // FORZAR HORARIOS INICIALES - Solución inmediata
        $this->horasDisponibles = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
            '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
            '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'
        ];

        // Temporalmente deshabilitado hasta ajustar a la nueva estructura
        $this->fechasSaturadas = [];
    }

    public function updatedFechaSeleccionada($value)
    {
        try {
            logger('=== INICIO updatedFechaSeleccionada ===');
            logger('Valor recibido: ' . $value);

            // Si el valor está vacío, limpiar horas y salir
            if (!$value) {
                logger('Valor vacío, limpiando horas');
                $this->horaSeleccionada = null;
                $this->horasDisponibles = [];
                return;
            }

            $this->horaSeleccionada = null;

            // FORZAR HORARIOS FIJOS - Solución temporal
            $this->horasDisponibles = [
                '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
                '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
                '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'
            ];

            logger('Horas forzadas: ' . json_encode($this->horasDisponibles));

        } catch (\Exception $e) {
            logger('Error en updatedFechaSeleccionada: ' . $e->getMessage());
            // Si hay error, también forzar horarios
            $this->horasDisponibles = [
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
            ];
        }
    }

    public function reservar()
    {
        logger('=== INICIO MÉTODO RESERVAR ===');
        logger('Fecha seleccionada: ' . $this->fechaSeleccionada);
        logger('Hora seleccionada: ' . ($this->horaSeleccionada ?? 'VACÍA'));
        logger('Detalle: ' . ($this->detalle ?? 'sin detalle'));
        logger('Usuario ID: ' . Auth::id());

        // Forzar una hora si no está seleccionada
        if (!$this->horaSeleccionada && !empty($this->horasDisponibles)) {
            $this->horaSeleccionada = $this->horasDisponibles[0];
            logger('Hora forzada a: ' . $this->horaSeleccionada);
        }

        $this->validate();

        try {
            logger('Validación pasada, creando reserva...');

            $reserva = Reserva::create([
                'user_id' => Auth::id(),
                'service_id' => 1, // Temporal
                'booking_date' => $this->fechaSeleccionada,
                'start_time' => $this->horaSeleccionada,
                'end_time' => date('H:i:s', strtotime($this->horaSeleccionada) + 3600),
                'status' => 'pending',
                'notes' => $this->detalle,
                'created_at' => now()
            ]);

            logger('Reserva creada con ID: ' . $reserva->id);
            logger('Datos de reserva: ' . json_encode($reserva->toArray()));

            // Redirigir a Mis Reservas con mensaje de éxito
            return redirect()->to('/mis-reservas')->with('success',
                '✅ ¡Reserva creada exitosamente! ID: ' . $reserva->id . ' para el ' . $this->fechaSeleccionada . ' a las ' . $this->horaSeleccionada
            );

        } catch (\Exception $e) {
            logger('Error al crear reserva: ' . $e->getMessage());
            logger('Stack trace: ' . $e->getTraceAsString());

            // Redirigir a Mis Reservas con mensaje de error
            return redirect()->to('/mis-reservas')->with('error',
                '❌ Error al crear la reserva: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.reservas.crear-reserva-creativo')->layout('layouts.app');
    }
}
