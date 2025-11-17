@php
    $ejecucion = $reporte->checklistEjecucion;
@endphp

@if (!$ejecucion)
    <div class="p-4 bg-yellow-100 border border-yellow-300 rounded">
        El conductor aún no ha completado el checklist.
    </div>
@else
    <livewire:filament.resources.checklist-ejecucion-resource.view 
        :record="$ejecucion" />
@endif
