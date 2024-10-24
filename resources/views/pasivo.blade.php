@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layout pagina="pasivo">
    <x-slot name="title">Analisis Pasivo</x-slot>
    
    <main class="container_formularios">
        @yield('content')
    </main>
    
</x-layout>