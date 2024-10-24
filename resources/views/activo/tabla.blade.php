@foreach ($resultados as $resultado)
    <x-resultado-card :resultado="$resultado" />
@endforeach