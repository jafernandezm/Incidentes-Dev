@extends('filtracion')

@section('content')
    @if (isset($resultados) && count($resultados) > 0)
        @if (count($resultados) > 0)
            <div class="results-container">
                <div class="results-box">
                    <div class="results-content">
                        <div class="mt-4">
                            <h2 class="results-title">Resultados de los Datos Filtrados:</h2>
                            @component('components.filtracion.table', ['resultados' => $resultados])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="results-container">
                <div class="results-box">
                    <div class="results-content">
                     
                        <div class="mt-4">
                            <h2 class="no-results">No se encontraron resultados.</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif



@endsection