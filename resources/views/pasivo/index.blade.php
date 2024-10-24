@extends('pasivo')

@section('content')

@vite(['resources/css/pasivo/pasivo.css']) 

<div class="container-pasivo">
    <h1 class="title">Escanear</h1>
    <section class="form-container">
        <form action="{{ route('pasivo.scanWebsite') }}" method="post" class="form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="cantidad">Cantidad de páginas</label>
                    <input id="cantidad" type="number" name="cantidad" value="10">
                </div>
                <div class="form-group">
                    <label for="excludeSites">Excluir sitios</label>
                    <div class="input-button-group">
                        <input id="excludeSites" type="text" name="excludeSites">
                        <button id="addButton">Agregar</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="dorks">Dorks</label>
                    <input id="dorks" type="text" name="dorks" placeholder="site:gob.bo">
                </div>
            </div>

            <input type="hidden" id="excludeSitesHidden" name="excludeSitesHidden">
            <ul id="excludeSitesList" class="exclude-sites-list"></ul>

            <div class="submit-buttonPasivo">
                <button id="submit-btn" class="submit-btn" onclick="showLoader()">Enviar</button>
                <div id="loader" style="display:none;">Cargando...</div>
            </div>
        </form>
        @if (isset($escaneo))
            <h2 class="result-title">Resultados del Escaneo:</h2>
            @include('components.resultado.escaneo')
        @endif
    </section>
</div>

<script>
    document.getElementById('addButton').addEventListener('click', function(event) {
        event.preventDefault();
        var excludeSitesInput = document.getElementById('excludeSites');
        var excludeSitesValue = excludeSitesInput.value;

        if (excludeSitesValue.trim() !== '') {
            var li = document.createElement('li');
            li.textContent = excludeSitesValue;
            document.getElementById('excludeSitesList').appendChild(li);
            excludeSitesInput.value = '';
        }
    });

    function showLoader() {
        document.getElementById('submit-btn').style.display = 'none';
        document.getElementById('loader').style.display = 'block';
    }
</script>

@endsection
