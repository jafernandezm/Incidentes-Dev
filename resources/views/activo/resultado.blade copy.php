<x-app-layout>
    @section('content')
        
        @if(!empty($message))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Información:</h2>
                        <div class="alert alert-info">
                            {{ $message }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($resultados) && count($resultados) > 0)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Resultados del Escaneo:</h2>
                        {{ __("Pasivos") }}
                        {{-- Incluir la vista de tabla --}}
                        @include('activo.tabla', ['resultados' => $resultados])
                    </div>
                </div>
            </div>
        @else
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Resultados del Escaneo:</h2>
                        <div class="alert alert-info">
                            No se encontraron resultados.
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
    @endsection
</x-app-layout>
