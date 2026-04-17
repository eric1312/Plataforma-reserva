<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;
use App\Notifications\CambioDisponibilidadNotification;
use App\Notifications\SedeSaturadaNotification;

class DashboardAdmin extends Component
{
    public $totalReservas;
    public $pendientes;
    public $confirmadas;
    public $canceladas;
    public $notificaciones;
    public $reservasPendientes;

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->totalReservas = Reserva::count();
        $this->pendientes = Reserva::where('status', 'pending')->count();
        $this->confirmadas = Reserva::where('status', 'confirmed')->count();
        $this->canceladas = Reserva::where('status', 'cancelled')->count();

        // Temporalmente deshabilitado hasta crear tabla notifications
        $this->notificaciones = collect([]);

        $this->reservasPendientes = Reserva::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();
    }

    public function confirmar($id)
    {
        $reserva = Reserva::find($id);
        if ($reserva && $reserva->status === 'pending') {
            $reserva->status = 'confirmed';
            $reserva->save();
            $this->cargarDatos();
        }
    }

    public function cancelar($id)
    {
        $reserva = Reserva::find($id);
        if ($reserva && $reserva->status !== 'cancelled') {
            $reserva->status = 'cancelled';
            $reserva->save();
            $this->cargarDatos();
        }
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
