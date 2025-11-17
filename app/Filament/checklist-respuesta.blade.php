<div class="py-2">

    @if (!$respuesta)
        <span class="text-gray-500 italic">Sin respuesta</span>

    @elseif ($item->tipoPregunta->codigo === 'BOOLEANO')
        <span class="font-semibold">
            {{ $respuesta->valor_booleano ? 'Sí' : 'No' }}
        </span>

    @elseif ($item->tipoPregunta->codigo === 'TEXTO')
        <span>{{ $respuesta->valor_texto }}</span>

    @elseif ($item->tipoPregunta->codigo === 'NUMERICO')
        <span>{{ $respuesta->valor_numero }}</span>

    @elseif ($item->tipoPregunta->codigo === 'OPCIONES')
        <span>{{ $respuesta->valor_texto }}</span>

    @elseif ($item->tipoPregunta->codigo === 'MULTIOPCION')
        <ul class="list-disc ms-4">
            @foreach (($respuesta->valor_json ?? []) as $valor)
                <li>{{ $valor }}</li>
            @endforeach
        </ul>

    @elseif ($item->tipoPregunta->codigo === 'IMAGEN')
        @if ($respuesta->valor_imagen)
            <img 
                src="{{ asset('storage/'.$respuesta->valor_imagen) }}" 
                class="w-40 rounded border"
            >
        @else
            <span class="text-gray-500 italic">Sin imagen</span>
        @endif

    @else
        <span class="text-gray-500 italic">Tipo no reconocido</span>
    @endif

</div>
