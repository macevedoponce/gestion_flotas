@if(!$solicitud)
    <div class="p-4 text-sm text-gray-600">
        No hay evidencias registradas por el conductor.
    </div>
@else

<div class="space-y-6">

    <!-- Foto KM -->
    @if ($solicitud->evidencia_foto_km_dev)
        <div>
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-2">Foto del tablero (KM)</h3>
            <img src="{{ Storage::url($solicitud->evidencia_foto_km_dev) }}"
                 class="rounded-lg w-full max-w-md border" />
        </div>
    @endif

    <!-- Fotos principales -->
    <div class="grid grid-cols-2 gap-4">

        @if ($solicitud->evidencia_foto_frontal_dev)
            <div>
                <h3 class="font-semibold">Frontal</h3>
                <img src="{{ Storage::url($solicitud->evidencia_foto_frontal_dev) }}"
                     class="rounded-lg border" />
            </div>
        @endif

        @if ($solicitud->evidencia_foto_posterior_dev)
            <div>
                <h3 class="font-semibold">Posterior</h3>
                <img src="{{ Storage::url($solicitud->evidencia_foto_posterior_dev) }}"
                     class="rounded-lg border" />
            </div>
        @endif

        @if ($solicitud->evidencia_foto_lat_izq_dev)
            <div>
                <h3 class="font-semibold">Lateral Izquierda</h3>
                <img src="{{ Storage::url($solicitud->evidencia_foto_lat_izq_dev) }}"
                     class="rounded-lg border" />
            </div>
        @endif

        @if ($solicitud->evidencia_foto_lat_der_dev)
            <div>
                <h3 class="font-semibold">Lateral Derecha</h3>
                <img src="{{ Storage::url($solicitud->evidencia_foto_lat_der_dev) }}"
                     class="rounded-lg border" />
            </div>
        @endif

    </div>

    <!-- Fotos adicionales -->
    @if (!empty($solicitud->evidencia_fotos_extra_dev))
        <div>
            <h3 class="font-semibold mb-2">Fotos adicionales</h3>
            <div class="grid grid-cols-3 gap-4">
                @foreach ($solicitud->evidencia_fotos_extra_dev as $foto)
                    <img src="{{ Storage::url($foto) }}"
                         class="rounded-lg border" />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Observaciones -->
    @if ($solicitud->evidencia_observaciones_dev)
        <div>
            <h3 class="font-semibold mb-1">Observaciones del conductor</h3>
            <p class="p-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                {{ $solicitud->evidencia_observaciones_dev }}
            </p>
        </div>
    @endif

    <!-- Ubicación textual -->
    @if ($solicitud->evidencia_ubicacion_text_dev)
        <div>
            <h3 class="font-semibold mb-1">Ubicación textual</h3>
            <p class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800">
                {{ $solicitud->evidencia_ubicacion_text_dev }}
            </p>
        </div>
    @endif

</div>
@endif