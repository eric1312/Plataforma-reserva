@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block">
                <h1 class="text-5xl font-bold text-white mb-4 float-animation">
                    <i class="fas fa-calendar-check mr-4"></i>
                    Crear Nueva Reserva
                </h1>
                <div class="bg-white/20 backdrop-blur rounded-full px-6 py-3 inline-block">
                    <p class="text-white text-lg">Complete el formulario para reservar su turno</p>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-lg mb-8 flex items-center">
                <i class="fas fa-check-circle text-3xl mr-4"></i>
                <div>
                    <p class="font-bold text-xl">¡Reserva Creada Exitosamente!</p>
                    <p class="text-lg">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg mb-8 flex items-center">
                <i class="fas fa-exclamation-triangle text-3xl mr-4"></i>
                <div>
                    <p class="font-bold text-xl">Error</p>
                    <p class="text-lg">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <form wire:submit="reservar" class="space-y-6">
                <!-- Selección de Fecha -->
                <div>
                    <label class="block text-lg font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                        Fecha de Reserva
                    </label>
                    <select wire:model="fechaSeleccionada" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione una fecha</option>
                        @foreach($fechasDisponibles as $fecha)
                            <option value="{{ $fecha }}">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y - l') }}</option>
                        @endforeach
                    </select>
                    @error('fechaSeleccionada')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Selección de Hora -->
                <div>
                    <label class="block text-lg font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Hora de Reserva
                    </label>
                    <select wire:model="horaSeleccionada" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccione una hora</option>
                        @foreach($horasDisponibles as $hora)
                            <option value="{{ $hora }}">{{ $hora }}</option>
                        @endforeach
                    </select>
                    @error('horaSeleccionada')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Detalles -->
                <div>
                    <label class="block text-lg font-semibold text-gray-700 mb-2">
                        <i class="fas fa-pen mr-2 text-blue-600"></i>
                        Detalles Adicionales
                    </label>
                    <textarea wire:model="detalle" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ingrese detalles adicionales..."></textarea>
                </div>

                <!-- Botón -->
                <div class="text-center">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Crear Reserva
                    </button>
                </div>
            </form>
        </div>

        <!-- Debug Info -->
        <div class="bg-gray-100 rounded-lg p-4 text-sm">
            <p><strong>Debug Info:</strong></p>
            <p>Fechas disponibles: {{ count($fechasDisponibles) }}</p>
            <p>Horas disponibles: {{ count($horasDisponibles) }}</p>
            <p>Fecha seleccionada: {{ $fechaSeleccionada ?? 'Ninguna' }}</p>
            <p>Hora seleccionada: {{ $horaSeleccionada ?? 'Ninguna' }}</p>
        </div>
    </div>
</div>

<style>
.float-animation {
    animation: float 3s ease-in-out infinite;
}
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
</style>
@endsection
