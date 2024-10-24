@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layout pagina="resultados">
    <x-slot name="title"> Escaneos</x-slot>

    <main class="container_formularios">
        @yield('content')
    </main>
    
</x-layout>