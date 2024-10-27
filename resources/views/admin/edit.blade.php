@extends('usuarios')

@section('template_title')
    {{ __('Update') }} tecnico
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="inicio_details">
                        <div class="titulo_titulacion">
                            Editar registro de tecnico
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('admin.update', $tecnico->id) }}">
                            @csrf
                            @method('PUT')  
                            <div class="content_data_documento">
                                    <div class="detalles_documento">
                                        <div class="rows-colums-2">
                                            <div class="input-box-documento">
                                                <label for="nombre" class="label">{{ __('Nombre') }}</label>
                                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $tecnico?->user->name) }}" id="nombre" placeholder="Nombre" required>
                                                {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                            <div class="input-box-documento">
                                                <label for="username" class="label">{{ __('Nombre de Usuario') }}</label>
                                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $tecnico?->user->username) }}" id="username" placeholder="Nombre de Usuario" required>
                                                {!! $errors->first('username', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                        </div>

                                        <div class="rows-colums-2">
                                            <div class="input-box-documento">
                                                <label for="celular" class="label">{{ __('Celular') }}</label>
                                                <input type="tel" name="celular" class="form-control @error('celular') is-invalid @enderror" value="{{ old('celular', $tecnico?->celular) }}" id="celular" placeholder="Celular (8 digitos sin codigo de pais)" pattern="[0-9]{8}" required minlength="8" maxlength="8">
                                                {!! $errors->first('celular', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                            <div class="input-box-documento">
                                                <label for="password" class="label">{{ __('Contraseña') }} <span style="font-size: 0.8rem">(Dejar en blanco si no desea cambiar)</span></label>
                                                <div class="password-container">
                                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Contraseña (Minimo 8 caracteres)" minlength="8">
                                                    <button type="button" id="togglePassword" class="toggle-password">Mostrar</button>
                                                </div>
                                                {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                        </div>
                                        <div class="rows-colums-2">
                                            <div class="input-box-documento">
                                                <label for="direccion" class="label">{{ __('Direccion') }}</label>
                                                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $tecnico?->direccion) }}" id="direccion" placeholder="Departamento" required>
                                                {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                            <div class="details_carrera_cu">
                                                <div class="input-box-documento">
                                                    <label for="ci" class="label">{{ __('Carnet de Identidad') }}</label>
                                                    <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci', $tecnico?->ci) }}" id="ci" placeholder="CI" required minlength="7" maxlength="8">
                                                    {!! $errors->first('ci', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                                </div>    
                                            </div>
                                        </div>
                             

                                        <div class="container_roles_config-rol2">
                                            <ul class="list-rol2">
                                                <label class="label">{{ __('Roles') }}</label>
                                                <ul class="list-rol23">
                                                    <li class="list-item-rol">
                                                        <input id="rol1" class="hidden-box-rol" type="checkbox" name="roles[]" value="admin" {{ in_array('admin', $tecnico?->user->roles->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                        <label for="rol1" class="check-label-rol">
                                                            <span class="check-label-text-rol">{{ __('Administrador') }}</span>
                                                            <span class="check-label-box-rol"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-item-rol">
                                                        <input id="rol2" class="hidden-box-rol" type="checkbox" name="roles[]" value="tecnico" {{ in_array('tecnico', $tecnico?->user->roles->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                        <label for="rol2" class="check-label-rol">
                                                            <span class="check-label-text-rol">{{ __('tecnico') }}</span>
                                                            <span class="check-label-box-rol"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-item-rol">
                                                        <input id="rol3" class="hidden-box-rol" type="checkbox" name="roles[]" value="usuario" {{ in_array('usuario', $tecnico?->user->roles->pluck('name')->toArray()) ? 'checked' : '' }}>
                                                        <label for="rol3" class="check-label-rol">
                                                            <span class="check-label-text-rol">{{ __('usuario') }}</span>
                                                            <span class="check-label-box-rol"></span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </div>
                                    </div>
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
                            </script>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection