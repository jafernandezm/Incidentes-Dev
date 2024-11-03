@extends('pasivo')

@section('content')

@vite(['resources/css/pasivo/pasivo.css']) 


<section class="form-container">
    <form action="{{ route('pasivo.scanWebsite') }}" method="post" class="form">
        @csrf
        <div class="form-flex">
            <div class="form-group">
                <label for="cantidad">Cantidad de páginas</label>
                <input id="cantidad" type="number" name="cantidad" value="10">
            </div>
            <div class="form-group">
                <label for="dorks">Dorks</label>
                <input id="dorks" type="text" name="dorks" placeholder="site:gob.bo">
            </div>
            <div class="form-group">
                <label for="excludeSites">Excluir sitios (opcional) *</label>
                <div class="input-button-group">
                    <input id="excludeSites" type="text" name="excludeSites" placeholder="https://www.ejemplo.com">
                    <button id="addButton" class="operation-button-pasivo"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
            <div class="form-group">
                <button id="submit-btn" class="submit-btn input_enviar" onclick="showLoader()">Escanear</button>
                <div class="loader"></div>
            </div>
   
        </div>

        <input type="hidden" id="excludeSitesHidden" name="excludeSitesHidden">
        <ul id="excludeSitesList" class="exclude-sites-list"></ul>

     
    </form>
    <h2 class="result-title">Resultados del Escaneo:</h2>

    @if (isset($escaneo))
        @include('components.resultado.escaneo')
    @endif

</section>


<script>
document.getElementById('addButton').addEventListener('click', function(event) {
    event.preventDefault();
    var excludeSitesInput = document.getElementById('excludeSites');
    var excludeSitesValue = excludeSitesInput.value;

    if (excludeSitesValue.trim() !== '') {
        let li = document.createElement('li');
        let buttonDelete = document.createElement('button');
        buttonDelete.className = 'operation-button-pasivo';
        buttonDelete.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        buttonDelete.addEventListener('click', function(event) {
            event.preventDefault();
            li.remove(); 
        });
        li.textContent = excludeSitesValue + ' ';
        li.appendChild(buttonDelete);

        document.getElementById('excludeSitesList').appendChild(li);
        excludeSitesInput.value = ''; 
    }
});


    function showLoader() {
        document.getElementById('submit-btn').style.display = 'none';
        document.querySelector('.loader').style.display = 'block';
    }
</script>

@endsection
