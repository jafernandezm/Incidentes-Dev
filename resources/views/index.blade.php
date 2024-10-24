@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layout pagina="temas">
    <x-slot name="title"> Inicio</x-slot>

    <main class="container_formularios">
        @yield('content')
    </main>
    
</x-layout>