@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layout pagina="perfil">
    @vite(['resources/css/perfil.css']) 
    <x-slot name="title">Perfil</x-slot>
    
    <div class="container_estudiantes">
        <h1>Editar Perfil</h1>
        <div class="container_createe">

                <form action="{{ route('perfil.actualizar', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')  
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" value="{{ $usuario->username }}" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña 
                            <small class="form-text text-muted" style="color: var(--grey);"> 
                                (Dejar vacío si no desea cambiar)
                            </small>
                        </label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="apikey" class="form-label">API Key</label>
                        <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Ingrese su API Key" value="{{ $usuario->api_key ?? '' }}">
                    </div>

                    <button type="submit" class="input_enviar">Actualizar</button>
                </form>
        </div>
    </div>
</x-layout>
