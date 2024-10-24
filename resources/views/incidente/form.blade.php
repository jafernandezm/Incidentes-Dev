@vite(['resources/css/incidente/form.css'])

<div class="inicio_details">
    <div class="titulo_titulacion">
        Registro de Incidente
    </div>
</div>
<div class="content_data_documento">
    <form action="{{ route('incidente.store') }}" method="POST">
        @csrf
        <fieldset>
            <legend>Datos del Incidente</legend>
            <div class="detalles_documento">
                <div class="input-box-documento">
                    <label for="tipo_id" class="label">Tipo de Incidente</label>
                    <select name="tipo_id" class="form-control" id="tipo_id" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-box-documento">
                    <label for="contenido" class="label">Contenido</label>
                    <textarea name="contenido" class="form-control" id="contenido" placeholder="Contenido" required></textarea>
                </div>
                <div class="input-box-documento">
                    <label for="descripcion" class="label">Descripción</label>
                    <textarea name="descripcion" class="form-control" id="descripcion" placeholder="Descripción" required></textarea>
                </div>
            </div>
        </fieldset>
        <div class="col-md-12 mt20 mt-2">
            <button type="submit" class="input_enviar">Enviar</button>
        </div>
    </form>
</div>
