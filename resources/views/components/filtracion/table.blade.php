@vite(['resources/css/filtracion/table.css'])
@php
    $filtracionesAgrupadas = collect($resultados)->groupBy('filtracion');
    $totalFiltraciones = $filtracionesAgrupadas->count();
@endphp
<style>
    .entryBy {
        position: relative;
    padding: 1.5rem;
    background-color: var(--white-strong);
    border-left: 30px solid var(--blue-strong);
    box-shadow: 5px 5px 5px 0px rgba(0, 0, 0, 0.40);
    opacity: 0.5;
    transition: all 0.6s ease;
    }
</style>
<div class="grid-container">
    @foreach ($filtracionesAgrupadas as $filtracion => $datos)
        <div class="card">
            <h3 class="card-title">Filtración: {{ $filtracion }}</h3>

            @if (!empty($datos[0]['informacion']))
                <div class="info-box">
                    <p class="info-title">Información adicional: <span
                            class="info-content">{{ $datos[0]['informacion'] }}</span></p>

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
                                    <div class="number-entry">
                                        @if ($loop->iteration < 10)
                                            0{{ $loop->iteration }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </div>
                                    @if (isset($entry['email']))
                                        <p class="entry-field">Email: <span>{{ $entry['email'] }}</span></p>
                                    @endif
                                    @if (isset($entry['has_password']))
                                        <p class="entry-field">Has Password:
                                            <span>{{ $entry['has_password'] ? 'Yes' : 'No' }}</span>
                                        </p>
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
                            <div class="entry ">
                                <div class="number-entry">
                                    @if ($loop->iteration < 10)
                                        0{{ $loop->iteration }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                @foreach ($data as $key => $value)
                                    <p class="entry-field">{{ ucfirst($key) }}: <span>{{ $value }}</span></p>
                                @endforeach
                            </div>
                        @else
                            <p>No hay datos disponibles.</p>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
