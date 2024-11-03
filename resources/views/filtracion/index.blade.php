@extends('filtracion')

@section('content')
    @vite(['resources/css/filtracion/filtracion.css'])
    <div class="container-filtracion">
        <form method="POST" action="{{ route('filtracion.store') }}" class="form">
            @csrf
            <div class="form-group-filter">
                <div>
                    <label for="tipo" class="label">Tipo</label>
                    <select name="tipo" id="tipo" class="select">
                        <option value="correo">Correo</option>
                        <option value="https">HTTPS</option>
                    </select>
                </div>

                <div class="input-wrapper">
                    <input type="text" name="consulta" placeholder="Escribe aquí..." class="input-text" />
                    <button type="submit" class="button-filter">Enviar</button>
                </div>
            </div>
        </form>
        
    </div>
   

    <div class="results-container">
        <h2 class="results-title">Resultados de los Datos Filtrados:</h2>
        <div class="results-box">
            <div class="results-content">
                <div class="mt-4">
                    @if (isset($resultados) && count($resultados) > 0)
                        @if (count($resultados) > 0)
                            @component('components.filtracion.table', ['resultados' => $resultados])
                            @endcomponent

                        @else

                            <h2 class="no-results">No se encontraron resultados.</h2>

                        @endif

        @endif
                </div>
            </div>
        </div>
    </div>


@endsection
