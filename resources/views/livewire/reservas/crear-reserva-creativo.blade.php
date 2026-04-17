<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Reserva - Sistema de Turnos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-scale {
            transition: all 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header Animado -->
        <div class="text-center mb-12">
            <div class="inline-block">
                <h1 class="text-5xl font-bold text-white mb-4 float-animation">
                    <i class="fas fa-calendar-check mr-4"></i>
                    Crear Nueva Reserva
                </h1>
                <div class="glass-effect rounded-full px-6 py-3 inline-block">
                    <p class="text-white text-lg">Complete el formulario para reservar su turno mágico ✨</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta Principal -->
        <div class="glass-effect rounded-3xl p-8 mb-8 hover-scale">
            <!-- Alertas -->
            @if(session()->has('message'))
                <div class="bg-green-500 text-white p-6 rounded-lg mb-6 flex items-center animate-pulse shadow-xl">
                    <i class="fas fa-check-circle mr-4 text-3xl"></i>
                    <div>
                        <p class="font-bold text-xl">¡Éxito!</p>
                        <p class="text-lg">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="bg-red-500 text-white p-6 rounded-lg mb-6 flex items-center animate-pulse shadow-xl">
                    <i class="fas fa-exclamation-triangle mr-4 text-3xl"></i>
                    <div>
                        <p class="font-bold text-xl">¡Error!</p>
                        <p class="text-lg">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Formulario Creativo -->
            <form wire:submit="reservar" class="space-y-8">
                <!-- Selección de Fecha -->
                <div class="relative">
                    <label class="text-white text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-calendar-day mr-3 text-yellow-300"></i>
                        📅 Fecha de Reserva
                    </label>
                    <div class="relative">
                        <select
                            id="fechaSeleccionada"
                            wire:model.defer="fechaSeleccionada"
                            class="w-full px-6 py-4 bg-white/20 backdrop-blur text-white rounded-xl border-2 border-white/30 focus:outline-none focus:ring-4 focus:ring-yellow-400 focus:border-transparent text-lg appearance-none cursor-pointer hover-scale"
                        >
                            <option value="">🌟 Seleccione una fecha mágica</option>
                            @foreach($fechasDisponibles as $fecha)
                                <option value="{{ $fecha }}" class="text-gray-800">
                                    🎯 {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($fecha)->format('l') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                            <i class="fas fa-chevron-down text-white text-xl"></i>
                        </div>
                    </div>
                    @error('fechaSeleccionada')
                        <div class="flex items-center mt-3 text-yellow-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Selección de Hora -->
                <div class="relative">
                    <label class="text-white text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-clock mr-3 text-yellow-300"></i>
                        🕐 Hora de Reserva
                    </label>
                    <div class="relative">
                        <select
                            id="horaSeleccionada"
                            wire:model="horaSeleccionada"
                            class="w-full px-6 py-4 bg-white/20 backdrop-blur text-white rounded-xl border-2 border-white/30 focus:outline-none focus:ring-4 focus:ring-yellow-400 focus:border-transparent text-lg appearance-none cursor-pointer hover-scale"
                        >
                            <option value="">⏰ Seleccione una hora</option>
                            @if(count($horasDisponibles) > 0)
                                @foreach($horasDisponibles as $hora)
                                    <option value="{{ $hora }}" class="text-gray-800">
                                        🎯 {{ $hora }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled class="text-gray-800">
                                    ❌ No hay horas disponibles para esta fecha
                                </option>
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                            <i class="fas fa-chevron-down text-white text-xl"></i>
                        </div>
                    </div>

                    <!-- Debug Info -->
                    <div class="mt-3 glass-effect rounded-lg p-3 text-white text-sm">
                        <p><i class="fas fa-info-circle mr-2 text-blue-300"></i>Horas disponibles: <span class="text-yellow-300 font-bold">{{ count($horasDisponibles) }}</span></p>
                        @if(count($horasDisponibles) > 0)
                            <p class="text-xs mt-1">Primeras horas: {{ implode(', ', array_slice($horasDisponibles, 0, 3)) }}...</p>
                        @endif
                    </div>
                    @error('horaSeleccionada')
                        <div class="flex items-center mt-3 text-yellow-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Detalles Adicionales -->
                <div class="relative">
                    <label class="text-white text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-pen-fancy mr-3 text-yellow-300"></i>
                        📝 Detalles Adicionales
                    </label>
                    <textarea
                        id="detalle"
                        wire:model="detalle"
                        rows="4"
                        class="w-full px-6 py-4 bg-white/20 backdrop-blur text-white rounded-xl border-2 border-white/30 focus:outline-none focus:ring-4 focus:ring-yellow-400 focus:border-transparent text-lg resize-none hover-scale"
                        placeholder="🌟 Cuéntanos sobre tu reserva mágica..."
                    ></textarea>
                    <div class="mt-2 text-right">
                        <span class="text-white/80 text-sm">{{ strlen($detalle ?? '') }}/255 caracteres</span>
                    </div>
                    @error('detalle')
                        <div class="flex items-center mt-3 text-yellow-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Botón Mágico -->
                <div class="text-center pt-6">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white py-4 px-8 rounded-full font-bold text-xl hover:from-yellow-500 hover:to-orange-600 transition duration-300 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed shadow-2xl transform hover:scale-110 hover:rotate-1"
                        wire:target="reservar"
                        :disabled="!fechaSeleccionada || !horaSeleccionada"
                    >
                        <span wire:loading wire:target="reservar" class="flex items-center">
                            <i class="fas fa-spinner fa-spin mr-3"></i>
                            🪄 Creando Magia...
                        </span>
                        <span wire:loading.remove wire:target="reservar" class="flex items-center">
                            <i class="fas fa-magic mr-3"></i>
                            ✨ Confirmar Reserva Mágica
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Panel de Información Mágica -->
        <div class="glass-effect rounded-3xl p-8 text-white">
            <div class="text-center mb-6">
                <i class="fas fa-sparkles text-4xl text-yellow-300 mb-4 float-animation"></i>
                <h3 class="text-2xl font-bold mb-4">🌟 Información Mágica de Disponibilidad</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-start">
                    <i class="fas fa-calendar-check text-green-300 mr-4 mt-1 text-xl"></i>
                    <div>
                        <p class="font-semibold">📅 Fechas Disponibles</p>
                        <p class="text-white/80">Las fechas se basan en la disponibilidad semanal de nuestros profesionales mágicos.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-clock text-blue-300 mr-4 mt-1 text-xl"></i>
                    <div>
                        <p class="font-semibold">🕐 Horas Flexibles</p>
                        <p class="text-white/80">Cada profesional tiene sus propios horarios de atención mágica.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope text-purple-300 mr-4 mt-1 text-xl"></i>
                    <div>
                        <p class="font-semibold">📧 Confirmación Inmediata</p>
                        <p class="text-white/80">Recibirás un email mágico una vez confirmada tu reserva.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Info (Temporal) -->
        <div class="glass-effect rounded-3xl p-6 text-white mt-8">
            <h4 class="font-bold mb-4 text-yellow-300">🔍 Información de Depuración</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p><i class="fas fa-calendar mr-2"></i>Fechas disponibles: <span class="text-yellow-300 font-bold">{{ count($fechasDisponibles) }}</span></p>
                    <p><i class="fas fa-clock mr-2"></i>Horas disponibles: <span class="text-yellow-300 font-bold">{{ count($horasDisponibles) }}</span></p>
                </div>
                <div>
                    <p><i class="fas fa-calendar-check mr-2"></i>Fecha seleccionada: <span class="text-green-300 font-bold">{{ $fechaSeleccionada ?? 'Ninguna' }}</span></p>
                    <p><i class="fas fa-clock mr-2"></i>Hora seleccionada: <span class="text-green-300 font-bold">{{ $horaSeleccionada ?? 'Ninguna' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Footer Creativo -->
        <div class="text-center mt-12 text-white">
            <div class="glass-effect rounded-full px-8 py-4 inline-block">
                <p class="text-lg">
                    <i class="fas fa-heart text-red-400 pulse-animation"></i>
                    Hecho con <span class="font-bold">❤️ Magia y Creatividad</span> por Eric Cirimarco
                </p>
                <p class="text-white/60 text-sm mt-2">© {{ date('Y') }} - Todos los derechos reservados mágicamente</p>
            </div>
        </div>
    </div>

    <script>
        // Efectos adicionales simples
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de entrada simple
            const elements = document.querySelectorAll('.hover-scale');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = 'all 0.6s ease';

                    setTimeout(() => {
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });
        });
    </script>
</body>
</html>
