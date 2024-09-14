@extends('usuarios')

@section('template_title')
    {{ $docente->name ?? __('Show') . " " . __('Docente') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="agregar_objetivos">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Ver Informacion del') }} Tecnico</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.users') }}"> {{ __('Atras') }}</a>
                            
                            
                        </div>
                    </div>

                    <div class="agregar_objetivos2">
                        <div class="form-group mb-2 mb20">
                            <strong>Nombre:</strong>
                            {{ $tecnico->user->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Nombre de Usuario:</strong>
                            {{ $tecnico->user->username }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Celular:</strong>
                            {{ $tecnico->celular }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Direccion:</strong>
                            {{ $tecnico->direccion }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Ci:</strong>
                            {{ $tecnico->ci }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Especialidad:</strong>
                            {{ $tecnico->especialidad }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Rol:</strong>
                            @foreach($tecnico->user->roles as $key => $rol)
                                <span>{{ $rol->name }}{{ !$loop->last ? ' - ' : '' }}</span>
                            @endforeach
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection