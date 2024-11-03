@php
    use Illuminate\Support\Facades\Auth;
@endphp
<div class="sidebar">
    <ul>
        <div class="logo">
            <img src="{{url('/')}}/img/Logotipo-AGETIC-blanco.png" alt="">
        </div>
        <div class="Menulist">
            @can('temas')
            <li style="--bg:#2196f3;" @if($pagina == 'temas') class="active" @endif>
                <a href="{{ route('temas') }}">
                    <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="text">Inicio</div>
                </a>
            </li>
            @endcan
        
            @can('admin.users')
            <li style="--bg:#2196f3;" @if($pagina == 'usuarios') class="active" @endif>
                <a href="{{ route('admin.users') }}">
                    <div class="icon"><i class="fa-solid fa-users"></i></div> 
                    <div class="text">Usuarios</div>
                </a>
            </li>
            @endcan
            @can('activo.index')
            <li style="--bg:#2196f3;" @if($pagina == 'activo') class="active" @endif>
                <a href="{{ route('activo.index') }}">
                     <div class="icon"><i class="fa-solid fa-chart-line"></i></div> <!-- Cambiado a 'fa-chart-line' -->
                    
                    <div class="text">Analisis Activo</div>
                </a>
            </li>
            @endcan
            @can('pasivo.index')
            <li style="--bg:#2196f3;" @if($pagina == 'pasivo') class="active" @endif>
                <a href="{{ route('pasivo.index') }}">
                    <div class="icon"><i class="fa-solid fa-chart-bar"></i></div> <!-- Cambiado a 'fa-chart-bar' -->
                    <div class="text">Analisis Pasivo</div>
                </a>
            </li>
            @endcan
            @can('filtracion.index')
            <li style="--bg:#2196f3;" @if($pagina == 'filtracion') class="active" @endif>
                <a href="{{ route('filtracion.index') }}">
                    <div class="icon"><i class="fa-solid fa-filter"></i></div> <!-- Cambiado a 'fa-filter' -->
                    <div class="text">Filtraciones</div>
                </a>
            </li>
            @endcan
            @can('escaneo.index')
            <li style="--bg:#2196f3;" @if($pagina == 'resultados') class="active" @endif>
                <a href="{{ route('escaneo.index') }}">
                    <div class="icon"><i class="fa-solid fa-list-alt"></i></div> <!-- Cambiado a 'fa-exclamation-circle' -->
                    <div class="text">Resultados</div>
                </a>
            </li>
            @endcan
            @can('incidente.index')
            <li style="--bg:#2196f3;" @if($pagina == 'incidente') class="active" @endif>
                <a href="{{ route('incidente.index') }}">
                    <div class="icon"><i class="fa-solid fa-exclamation-triangle"></i></div> <!-- Cambiado a 'fa-exclamation-circle' -->
                    <div class="text">Incidente Registrados</div>
                </a>
            </li>
           
        </div>
        <div class="logout">
        @endcan
            @if(Auth::check())
        
                <form action="{{ route('logout')}}" method="POST">
                    @csrf
                    <button type="submit"  style="background: none; border: none; cursor: pointer;">
                        <div class="button_log_out">
                            <div class="icon"><i class="fa-solid fa-sign-out"></i></div>
                            <div class="text">Cerrar Sesión</div>
                        </div>
                    </button>
                </form>
            
            @endif
        </div>
    </ul>
</div>	