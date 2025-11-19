@php
$solicitud = $getRecord();
@endphp

@if (! $solicitud->requiere_conductor)
<div class="fi-section rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm p-6 space-y-6">

    <div class="fi-section-header">
        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Conductor externo proporcionado en la solicitud
        </div>
        <div class="fi-description text-sm text-gray-600 dark:text-gray-400 mt-1">
            Este conductor será creado automáticamente y se marcará como <strong>OCUPADO</strong>.
        </div>
    </div>

    <div class="fi-section-content">
        <div class="grid grid-cols-2 gap-4">

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">
                    Nombre completo
                </div>
                <div class="p-2 rounded bg-gray-50 dark:bg-gray-800 text-sm">
                    {{ $solicitud->conductor_externo_nombres }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">
                    DNI
                </div>
                <div class="p-2 rounded bg-gray-50 dark:bg-gray-800 text-sm">
                    {{ $solicitud->conductor_externo_dni }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">
                    Celular
                </div>
                <div class="p-2 rounded bg-gray-50 dark:bg-gray-800 text-sm">
                    {{ $solicitud->conductor_externo_celular }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">
                    Licencia
                </div>
                <div class="p-2 rounded bg-gray-50 dark:bg-gray-800 text-sm">
                    {{ $solicitud->conductor_externo_licencia }}
                </div>
            </div>

            <div class="col-span-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">
                    Acceso App (usuario y contraseña inicial)
                </div>
                <div class="flex gap-4">
                    <div class="p-2 rounded bg-gray-50 dark:bg-gray-800 text-sm w-full">
                        Usuario: {{ $solicitud->conductor_externo_dni }}
                    </div>
                    <div class="p-2 rounded bg-gray-50 dark:bg-gray-800 text-sm w-full">
                        Contraseña: {{ $solicitud->conductor_externo_dni }}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endif
