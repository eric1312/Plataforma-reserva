<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Disponibilidad;
use App\Models\User;

class Disponibilidades extends Component
{
    public $user_id, $day_of_week, $start_time, $end_time, $is_available = 1;
    public $disponibilidades;
    public $editId = null;

    protected $rules = [
        'user_id' => 'required|exists:Users,id',
        'day_of_week' => 'required|integer|min:0|max:6',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'is_available' => 'boolean',
    ];

    protected $messages = [
        'user_id.required' => 'El profesional es obligatorio.',
        'day_of_week.required' => 'El día de la semana es obligatorio.',
        'start_time.required' => 'La hora de inicio es obligatoria.',
        'end_time.required' => 'La hora de fin es obligatoria.',
        'end_time.after' => 'La hora de fin debe ser después de la hora de inicio.',
    ];

    public function mount()
    {
        $this->loadDisponibilidades();
    }

    public function loadDisponibilidades()
    {
        $this->disponibilidades = Disponibilidad::with('user')
            ->orderBy('user_id')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }

    public function save()
    {
        $this->validate();

        if ($this->editId) {
            $disp = Disponibilidad::find($this->editId);
            $disp->update([
                'user_id' => $this->user_id,
                'day_of_week' => $this->day_of_week,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'is_available' => $this->is_available,
            ]);
            $this->editId = null;
        } else {
            Disponibilidad::create([
                'user_id' => $this->user_id,
                'day_of_week' => $this->day_of_week,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'is_available' => $this->is_available,
            ]);
        }

        $this->reset(['user_id', 'day_of_week', 'start_time', 'end_time', 'is_available']);
        $this->loadDisponibilidades();
    }

    public function edit($id)
    {
        $disp = Disponibilidad::findOrFail($id);
        $this->editId = $id;
        $this->user_id = $disp->user_id;
        $this->day_of_week = $disp->day_of_week;
        $this->start_time = $disp->start_time;
        $this->end_time = $disp->end_time;
        $this->is_available = $disp->is_available;
    }

    public function delete($id)
    {
        Disponibilidad::destroy($id);
        $this->loadDisponibilidades();
    }

    public function render()
    {
        $profesionales = User::where('role', 'professional')->get();
        $diasSemana = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado'
        ];

        return view('livewire.admin.disponibilidades', [
            'profesionales' => $profesionales,
            'diasSemana' => $diasSemana
        ]);
    }
}
