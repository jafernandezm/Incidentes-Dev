@extends('usuarios')

{{-- @section('template_title')
    {{ ?? __('Show') . " " . __('Docente') }}
@endsection --}}

@section('content')
    <section class="content container-fluid">
        <div class="agregar_objetivos">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Ver Informacion del') }} incidente</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('incidente.index') }}"> {{ __('Atras') }}</a>
                        </div>
                    </div>
                    <div class="agregar_objetivos2">
                        <div class="form-group mb-2 mb20">
                            <strong>Tipo Incidente:</strong>
                            {{ $incidente->tipo->nombre }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Contenido:</strong>
                            {{ $incidente->contenido }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Descripcion:</strong>
                            {{ $incidente->descripcion }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Fecha:</strong>
                            {{ $incidente->fecha }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection