@vite(['resources/css/resultado/resultado.css']) 

<div class="container">
    <div class="resultados-escaneo">
        <h2>{{ $escaneo['url'] }}</h2>
 
        <div class="grid">
            <!-- Parámetros del Escaneo -->
            <div class="card-resultado a100">
                <div class="card__content-text">
                    <ul>
                        <li><span>Dirección IP:</span> {{ $detalles['IP'] ?? 'Desconocido' }}</li>
                        <li><span>País:</span> {{ $detalles['country'] ?? 'Desconocido' }}</li>
                        <li><span>Servidor:</span> {{ $detalles['HTTPServer'] ?? 'Desconocido' }}</li>
                        <li><span>CMS:</span> 
                            @if(isset($detalles['CMS']) && is_array($detalles['CMS']))
                                {{ implode(', ', array_map(fn($cms) => htmlspecialchars($cms['TEC'] . ' ' . ($cms['version'] ?? 'Desconocido')), $detalles['CMS'])) }}
                            @else
                                Desconocido
                            @endif
                        </li>
                        <li><span>Fecha del escaneo:</span> {{ \Carbon\Carbon::parse($escaneo['fecha'])->format('D Y/m/d') }}</li>
                    </ul>
                </div>
                <div class="card__content">
                    <div class="card__content-inner">
                        <div class="card__title">Parámetros del Escaneo</div>
                    </div>
                </div>
            </div>

            <div class="card-resultado a100">
                <div class="card__content-text">
                    <ul>
                        <li><span>Archivos analizados:</span> {{ $detalles['cantidad'] ?? 0 }}</li>
                        <li><span>Archivos maliciosos:</span> {{ $detalles['cantidadIncidentes'] ?? 0 }}</li>
                        <li><span>Archivos limpios:</span> {{ ($detalles['cantidad'] ?? 0) - ($detalles['cantidadIncidentes'] ?? 0) }}</li>
                        <li><span>Enlaces externos detectados:</span> {{ $detalles['external_links_detected'] ?? 0 }}</li>

                        @forelse ($escaneo->resultados as $resultado)
                            <li><span>Información:</span> {{ $resultado->detalle ?? 'No se encontraron detalles' }}</li>
                        @empty
                            <li>No se encontraron resultados</li>
                        @endforelse
                    </ul>
                    <div>
                        <form action="{{ route('escaneo.show', ['id' => $escaneo->id]) }}" method="GET">
                            <button type="submit" class="ver-boton">Ver todos</button>
                        </form>
                    </div>
                </div>
                <div class="card__content">
                    <div class="card__content-inner">
                    <div class="card__title">DETALLES DE DETECCIÓN</div>
                    </div>
                </div>
            </div>

            {{-- <!-- Estado de Lista Negra -->
            <div class="estado-lista-negra">
                <h3>ESTADO DE LISTA NEGRA</h3>
                <ul>
                    <li><span>Quttera Labs:</span> {{ $escaneo['estado_lista_negra']['quttera'] ?? 'Desconocido' }}</li>
                    <li><span>Zeus Tracker:</span> {{ $escaneo['estado_lista_negra']['zeus'] ?? 'Desconocido' }}</li>
                </ul>
            </div> --}}
        </div>
    </div>
</div>
