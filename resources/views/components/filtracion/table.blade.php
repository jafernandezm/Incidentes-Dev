
@vite(['resources/css/filtracion/table.css'])
@php
    $filtracionesAgrupadas = collect($resultados)->groupBy('filtracion');
    $totalFiltraciones = $filtracionesAgrupadas->count();
@endphp

<div class="grid-container">
    @foreach ($filtracionesAgrupadas as $filtracion => $datos)
        <div class="card">
            <h3 class="card-title">Filtración: {{ $filtracion }}</h3>

            @if (!empty($datos[0]['informacion']))
                <div class="info-box">
                    <p class="info-title">Información adicional: <span class="info-content">{{ $datos[0]['informacion'] }}</span></p>
                    
                </div>
            @endif

            @foreach ($datos as $result)
                <div class="cards-filters">
                    @php
                        $data = json_decode($result['data'], true);
                    @endphp

                    @if ($filtracion === 'BreachDirectory')
                        @if (!empty($data))
                            @foreach ($data as $entry)
                                <div class="entry">
                                    <div class="number-entry"> @if ( $loop->iteration < 10 ) 0{{ $loop->iteration }} @else {{ $loop->iteration }} @endif </div>
                                    @if (isset($entry['email']))
                                        <p class="entry-field">Email: <span>{{ $entry['email'] }}</span></p>
                                    @endif
                                    @if (isset($entry['has_password']))
                                        <p class="entry-field">Has Password: <span>{{ $entry['has_password'] ? 'Yes' : 'No' }}</span></p>
                                    @endif
                                    @if (isset($entry['sources']))
                                        <p class="entry-field">Sources: <span>{{ $entry['sources'] }}</span></p>
                                    @endif
                                    @if (isset($entry['password']))
                                        <p class="entry-field">Password: <span>{{ $entry['password'] }}</span></p>
                                    @endif
                                    @if (isset($entry['sha1']))
                                        <p class="entry-field">sha1: <span>{{ $entry['sha1'] }}</span></p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No hay datos disponibles.</p>
                        @endif
                    @else
                        @if (!empty($data))
                            @foreach ($data as $key => $value)
                                <div class="entry">
                                    <p class="entry-field">{{ ucfirst($key) }}: <span>{{ $value }}</span></p>
                                </div>
                            @endforeach
                        @else
                            <p>No hay datos disponibles.</p>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
