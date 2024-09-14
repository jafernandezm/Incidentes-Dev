<div class="inicio_details">
    <div class="titulo_titulacion">
        Registro de Usuario
    </div>
</div>
<div class="content_data_documento">
    <fieldset>
        <legend>Datos del Usuario</legend>
        <div class="detalles_documento">
            <div class="input-box-documento">
                <label for="nombre" class="label">{{ __('Nombre') }}</label>
                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" id="nombre" placeholder="Nombre" required>
                {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="input-box-documento">
                <label for="username" class="label">{{ __('Nombre de Usuario') }}</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" id="username" placeholder="Nombre de Usuario" required>
                {!! $errors->first('username', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="input-box-documento">
                <label for="password" class="label">{{ __('Contraseña') }}</label>
                <div class="password-container">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña (Minimo 8 caracteres)" required minlength="8">
                    <button type="button" id="togglePassword" class="toggle-password" style=" margin-bottom:2.2rem;">Mostrar</button>
                </div>
                {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <div class="details_carrera_cu">
                <div class="input-box-documento" style="width: 48%;">
                    <label for="celular" class="label">{{ __('Celular') }}</label>
                    <input type="tel" name="celular" class="form-control @error('celular') is-invalid @enderror" value="{{ old('celular') }}" id="celular" placeholder="Celular (8 digitos sin codigo de pais)" pattern="[0-9]{8}" required minlength="8" maxlength="8">
                    {!! $errors->first('celular', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
                <div class="input-box-documento" style="width: 48%;">
                    <label for="direccion" class="label">{{ __('Direccion') }}</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" id="direccion" placeholder="Direccion" required>
                    {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
            <div class="details_carrera_cu">
                <div class="input-box-documento" style="width: 48%;">
                    <label for="ci" class="label">{{ __('Carnet de Identidad') }}</label>
                    <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci') }}" id="ci" placeholder="CI (Minimo 7 digitos, Maximo 8 digitos)" required minlength="7" maxlength="8">
                    {!! $errors->first('ci', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="container_roles_config-rol2">
                <ul class="list-rol2">
                    <label class="label">Roles</label>
                    <ul class="list-rol23">
                        <li class="list-item-rol">
                            <input id="rol1" class="hidden-box-rol" type="checkbox" name="roles[]" value="admin">
                            <label for="rol1" class="check-label-rol">
                                <span class="check-label-text-rol">Administrador</span>
                                <span class="check-label-box-rol"></span>
                            </label>
                        </li>
                        <li class="list-item-rol">
                            <input id="rol2" class="hidden-box-rol" type="checkbox" name="roles[]" value="tecnico">
                            <label for="rol2" class="check-label-rol">
                                <span class="check-label-text-rol">Tecnico</span>
                                <span class="check-label-box-rol"></span>
                            </label>
                        </li>
                        <li class="list-item-rol">
                            <input id="rol3" class="hidden-box-rol" type="checkbox" name="roles[]" value="usuario">
                            <label for="rol3" class="check-label-rol">
                                <span class="check-label-text-rol">Usuario</span>
                                <span class="check-label-box-rol"></span>
                            </label>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </fieldset>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="input_enviar">{{ __('Enviar') }}</button>
    </div>
</div>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Mostrar' : 'Ocultar';
    });

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.hidden-box-rol');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        const nombreField = document.getElementById('nombre');
        const form = document.getElementById('registrationForm');

        nombreField.addEventListener('input', function(event) {
            if (!nombreField.value.startsWith('Ing. ')) {
                nombreField.value = 'Ing. ' + nombreField.value.replace(/^Ing\. /, '');
            }
        });

        form.addEventListener('submit', function(event) {
            if (!nombreField.value.startsWith('Ing. ')) {
                nombreField.value = 'Ing. ' + nombreField.value;
            }
            console.log('Nombre modificado a: ', nombreField.value); // Mensaje para verificar el cambio
        });
    });
</script>