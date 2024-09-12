@php
    use Illuminate\Support\Facades\Auth;
@endphp
<div class="header">
    <div class="title_process">{{ $title }}</div>
    <div class="user_profile">
        <div class="line"></div>
        <div class="dropdown">
        @if(Auth::check())
            <div class="name_user_true">{{ Auth::user()->name }}</div>
            <div class="name_user">{{ Auth::user()->username }}</div>
        @endif
            <div class="dropdown-conten" hidden>
            <a href="">
                <div style="padding-left:1rem; display: flex; flex-direction: row;">
                    <div class="user_icon" style="padding-right: 1rem">
                        <ion-icon name="person" style="font-size: 1.5rem"></ion-icon>
                    </div>
                    <a href="">Perfil</a>
                </div>
            </a>
            </div>
        </div>  
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.querySelector('.dropdown');
        const dropdownConten = document.querySelector('.dropdown-conten');
        dropdownConten.hidden = true;
        dropdown.addEventListener('click', () => {
            dropdownConten.hidden = !dropdownConten.hidden;
        });
    });
</script>