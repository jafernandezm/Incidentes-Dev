
@extends('resultado')

@vite(['resources/css/table_docen.css']) 

@section('content')
    @include('components.resultado.tables', ['escaneos' => $escaneos])
@endsection