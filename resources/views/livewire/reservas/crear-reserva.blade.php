<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <div class="flex items-center mb-6">
                <div class="bg-blue-600 text-white rounded-full p-3 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        @if($tituloFuncion)
                            Reservar: {{ $tituloFuncion }}
                        @else
                            Crear Nueva Reserva
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1">Complete el formulario para reservar su turno</p>
                </div>
            </div>

            <!-- Alertas -->
            @if(session()->has('message'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-700">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario -->
            <form wire:submit="reservar" class="space-y-8">
                <!-- Selección de Fecha -->
                <div class="space-y-2">
                    <label for="fechaSeleccionada" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Fecha de Reserva
                    </label>
                    <div class="relative">
                        <select
                            id="fechaSeleccionada"
                            wire:model.live="fechaSeleccionada"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none bg-white"
                        >
                            <option value="">📅 Seleccione una fecha disponible</option>
                            @foreach($fechasDisponibles as $fecha)
                                <option value="{{ $fecha }}">
                                    {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($fecha)->format('l') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('fechaSeleccionada')
                        <div class="flex items-center mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Selección de Hora -->
                <div class="space-y-2">
                    <label for="horaSeleccionada" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hora de Reserva
                    </label>
                    <div class="relative">
                        <select
                            id="horaSeleccionada"
                            wire:model="horaSeleccionada"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 appearance-none bg-white"
                            @if(!$fechaSeleccionada) disabled @endif
                        >
                            <option value="">🕐 Seleccione una hora</option>
                            @foreach($horasDisponibles as $hora)
                                <option value="{{ $hora }}">{{ $hora }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @if(!$fechaSeleccionada)
                        <p class="text-sm text-gray-500 italic">Seleccione primero una fecha para ver las horas disponibles</p>
                    @endif
                    @error('horaSeleccionada')
                        <div class="flex items-center mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Detalles Adicionales -->
                <div class="space-y-2">
                    <label for="detalle" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Detalles Adicionales (opcional)
                    </label>
                    <textarea
                        id="detalle"
                        wire:model="detalle"
                        rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-none"
                        placeholder="Ingrese cualquier detalle adicional para su reserva (motivo de consulta, síntomas, etc.)..."
                    ></textarea>
                    <p class="text-xs text-gray-500">{{ strlen($detalle ?? '') }}/255 caracteres</p>
                    @error('detalle')
                        <div class="flex items-center mt-2 text-red-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Botón de Envío -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition duration-200 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed shadow-lg transform hover:scale-[1.02] transition-transform"
                        :disabled="!@this.fechaSeleccionada || !@this.horaSeleccionada || @this.processing"
                    >
                        <span wire:loading wire:target="reservar" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Procesando reserva...
                        </span>
                        <span wire:loading.remove wire:target="reservar" class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Confirmar Reserva
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Información de Disponibilidad -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 rounded-lg p-6 shadow-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Información de Disponibilidad</h3>
                    <div class="text-blue-700 text-sm space-y-1">
                        <p>• Las fechas disponibles se basan en la disponibilidad semanal de los profesionales.</p>
                        <p>• Solo se muestran los próximos 30 días con disponibilidad confirmada.</p>
                        <p>• Una vez confirmada la reserva, recibirá un email de confirmación.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
