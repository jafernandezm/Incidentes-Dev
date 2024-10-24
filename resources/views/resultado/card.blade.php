@php
    use Illuminate\Support\Facades\Auth;
@endphp
@extends('resultado')

@vite(['resources/css/table_docen.css']) 

@section('content')
    @include('components.resultado.resultado')
@endsection