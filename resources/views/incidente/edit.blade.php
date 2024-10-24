@extends('incidente')

@section('template_title')
    {{ __('Editar') }} Incidente
@endsection

@vite(['resources/css/incidente/form.css'])

@section('content')
    <section class="content container-fluid">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="inicio_details">
                    <div class="titulo_titulacion">
                        Editar registro de incidente
                    </div>
                </div>
                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('incidente.update', $incidente->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="content_data_documento">
                            <fieldset>
                                <legend>Datos del incidente</legend>
                                <div class="detalles_documento">
                                    <div class="input-box-documento">
                                        <label for="tipo_id" class="label">Tipo de Incidente</label>
                                        <select name="tipo_id" class="form-control" id="tipo_id" required>
                                            <option value="">Seleccione un tipo</option>
                                            @foreach ($tipos as $tipo)
                                                <option value="{{ $tipo->id }}"
                                                    {{ $tipo->id == old('tipo_id', $incidente->tipo_id) ? 'selected' : '' }}>
                                                    {{ $tipo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input-box-documento">
                                        <label for="contenido" class="label">Contenido</label>
                                        <textarea name="contenido" class="form-control" id="contenido" placeholder="Contenido" required>{{ old('contenido', $incidente->contenido) }}</textarea>
                                    </div>

                                    <div class="input-box-documento">
                                        <label for="descripcion" class="label">Descripción</label>
                                        <textarea name="descripcion" class="form-control" id="descripcion" placeholder="Descripción" required>{{ old('descripcion', $incidente->descripcion) }}</textarea>
                                    </div>

                                    <!-- Otros campos del incidente si es necesario -->

                                </div>
                            </fieldset>

                            <div class="col-md-12 mt20 mt-2">
                                <button type="submit" class="input_enviar">{{ __('Actualizar') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
