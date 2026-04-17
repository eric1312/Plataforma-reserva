<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CancelacionReservaMail;
use App\Models\Reserva;
use Carbon\Carbon;
use Livewire\WithPagination;

class MisReservas extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $reserva_id;
    public $nueva_fecha, $nueva_hora;
    public $modalReprogramarAbierto = false;

    public function abrirModalReprogramar($id)
    {
        $this->reserva_id = $id;
        $this->modalReprogramarAbierto = true;
    }

    public function reprogramarReserva()
    {
        $hora = \Carbon\Carbon::parse($this->nueva_hora);

        if ($hora->lt(Carbon::createFromTime(9, 0)) || $hora->gt(Carbon::createFromTime(17, 0))) {
            $this->addError('hora', 'El horario debe estar entre 9:00 y 17:00.');
            return;
        }

        $reserva = Reserva::find($this->reserva_id);

        $reservada = Reserva::where('user_id', Auth::id())
            ->where('booking_date', $this->nueva_fecha)
            ->where('start_time', $this->nueva_hora)
            ->where('id', '!=', $this->reserva_id)
            ->exists();

        if ($reservada) {
            $this->addError('nueva_fecha', 'Ya tienes una reserva para esta fecha y hora.');
            return;
        }

        $yaReservado = Reserva::where('service_id', $reserva->service_id)
            ->whereDate('booking_date', $this->nueva_fecha)
            ->whereTime('start_time', $this->nueva_hora)
            ->count();

        $capacidad = 10; // Capacidad fija temporal

        if ($yaReservado >= $capacidad) {
            $this->addError('nueva_fecha', 'La nueva fecha y hora no están disponibles.');
            return;
        }

        // Actualizar reserva directamente sin historial
        $reserva->update([
            'booking_date' => $this->nueva_fecha,
            'start_time' => $this->nueva_hora,
        ]);

        $this->modalReprogramarAbierto = false;
        session()->flash('message', 'Reserva reprogramada exitosamente.');
    }

    public function cancelar($id)

    {
        $reserva = Reserva::where('user_id', Auth::id())->findOrFail($id);
        $reserva->status = 'cancelled';
        $reserva->save();

        // Comentar envío de correo para evitar errores
        // Mail::to($reserva->user->email)->send(new CancelacionReservaMail($reserva));

        session()->flash('message', 'Reserva cancelada exitosamente.');
    }

    public function render()
    {
        $reservas = Reserva::where('user_id', Auth::id())
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('livewire.mis-reservas', ['reservas' => $reservas]);
    }
}
