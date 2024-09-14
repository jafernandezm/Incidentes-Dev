@php
    use Illuminate\Support\Facades\Auth;
@endphp
<div class="sidebar">
    <ul>
        <div class="logo"><img src="{{url('/')}}/img/logo-agetic.jpg" alt=""></div>
        <div class="Menulist">
            <h2 class="title_gestion">Proyecto</h2>
  
            <li style="--bg:#2196f3;" @if($pagina == 'temas') class="active" @endif>
                <a href="{{ route('temas') }}">
                    <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="text">Inicio</div>
                </a>
            </li>

            <li style="--bg:#2196f3;" @if($pagina == 'usuarios') class="active" @endif>
                <a href="{{ route('admin.users') }}">
                    <div class="icon"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="text">usuarios</div>
                </a>
            </li>
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