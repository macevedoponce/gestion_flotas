<div class="space-y-4">

    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
        Evidencias enviadas por el Jefe de Proyecto
    </h3>

    <div class="grid grid-cols-2 gap-4">

        @php
            $rec = $getRecord();
        @endphp

        <!-- Foto de KM -->
        <div>
            <p class="font-semibold text-sm text-gray-600 dark:text-gray-300">Foto Tablero (KM)</p>
            @if ($rec->evidencia_foto_km)
                <img src="{{ asset('storage/' . $rec->evidencia_foto_km) }}"
                     class="rounded-lg shadow border dark:border-gray-700">
            @else
                <p class="text-red-500 text-sm">No enviada</p>
            @endif
        </div>

        <!-- Frontal -->
        <div>
            <p class="font-semibold text-sm text-gray-600 dark:text-gray-300">Frontal</p>
            @if ($rec->evidencia_foto_frontal)
                <img src="{{ asset('storage/' . $rec->evidencia_foto_frontal) }}"
                     class="rounded-lg shadow border dark:border-gray-700">
            @endif
        </div>

        <!-- Posterior -->
        <div>
            <p class="font-semibold text-sm text-gray-600 dark:text-gray-300">Posterior</p>
            @if ($rec->evidencia_foto_posterior)
                <img src="{{ asset('storage/' . $rec->evidencia_foto_posterior) }}"
                     class="rounded-lg shadow border dark:border-gray-700">
            @endif
        </div>

        <!-- Laterales -->
        <div>
            <p class="font-semibold text-sm text-gray-600 dark:text-gray-300">Lateral Izquierda</p>
            @if ($rec->evidencia_foto_lat_izq)
                <img src="{{ asset('storage/' . $rec->evidencia_foto_lat_izq) }}"
                     class="rounded-lg shadow border dark:border-gray-700">
            @endif
        </div>

        <div>
            <p class="font-semibold text-sm text-gray-600 dark:text-gray-300">Lateral Derecha</p>
            @if ($rec->evidencia_foto_lat_der)
                <img src="{{ asset('storage/' . $rec->evidencia_foto_lat_der) }}"
                     class="rounded-lg shadow border dark:border-gray-700">
            @endif
        </div>

        <!-- Extras -->
        <div class="col-span-2">
            <p class="font-semibold text-sm text-gray-600 dark:text-gray-300">Fotos adicionales</p>

            @if (!empty($rec->evidencia_fotos_extra))
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($rec->evidencia_fotos_extra as $img)
                        <img src="{{ asset('storage/' . $img) }}"
                             class="rounded-md shadow border dark:border-gray-700">
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm">No se enviaron fotos adicionales.</p>
            @endif
        </div>

    </div>

    <div class="mt-3">
        <p class="text-sm text-gray-700 dark:text-gray-300">
            <strong>Ubicación textual:</strong> 
            {{ $rec->evidencia_ubicacion_text ?? 'No registrada' }}
        </p>
    </div>

</div>
