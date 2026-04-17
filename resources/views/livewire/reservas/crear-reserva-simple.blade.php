<div>
    <h1>Crear Reserva - Versión Simple</h1>
    
    @if(session()->has('message'))
        <div style="background: green; color: white; padding: 10px; margin: 10px 0;">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="reservar">
        <div>
            <label>Fecha:</label>
            <select wire:model="fechaSeleccionada">
                <option value="">Seleccione fecha</option>
                @foreach($fechasDisponibles as $fecha)
                    <option value="{{ $fecha }}">{{ $fecha }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label>Hora:</label>
            <select wire:model="horaSeleccionada">
                <option value="">Seleccione hora</option>
                @foreach($horasDisponibles as $hora)
                    <option value="{{ $hora }}">{{ $hora }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label>Detalles:</label>
            <textarea wire:model="detalle"></textarea>
        </div>
        
        <button type="submit">Crear Reserva</button>
    </form>
    
    <div>
        <h3>Debug Info:</h3>
        <p>Fechas disponibles: {{ count($fechasDisponibles) }}</p>
        <p>Horas disponibles: {{ count($horasDisponibles) }}</p>
        <p>Fecha seleccionada: {{ $fechaSeleccionada ?? 'Ninguna' }}</p>
        <p>Hora seleccionada: {{ $horaSeleccionada ?? 'Ninguna' }}</p>
    </div>
</div>
