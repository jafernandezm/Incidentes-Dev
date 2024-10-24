@extends('activo')


@section('content')
    <div class="container-activo">
        <div class="form-container">
            <div class="form-wrapper">
                <form action="{{ route('activo.scanWebsite') }}" method="post" class="form" onsubmit="showLoader()">
                    @csrf
                    <div class="form-group">
                        <label for="protocol" class="form-label">Protocol</label>
                        <div class="form-field">
                            <select name="protocol" id="protocol" class="select-field">
                                <option value="http">http</option>
                                <option value="https" selected>https</option>
                            </select>
                            <input type="text" name="url" placeholder="example.bo" class="input-field" />
                            <button type="submit" id="submit-btn" class="submit-button">Escanear</button>
                        </div>
                    </div>
                </form>
                @if (isset($escaneo))
                    <h2 class="result-title">Resultados del Escaneo:</h2>
                    @include('components.resultado.escaneo')
                @endif
            </div>
        </div>
    </div>
    <div id="loader" class="loader-container" style="display:none;">
        <div class="loader-content">
            <div class="loader"></div>
            <p>Analizando sitio web. Espere por favor.</p>
        </div>
    </div>
    <script>
        document.getElementById('scanForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el envío por defecto del formulario

            // Mostrar el loader
            document.getElementById('submit-btn').style.display = 'none';
            document.getElementById('loader').style.display = 'block';

            // Obtener los datos del formulario
            const formData = new FormData(this);

            // Enviar la solicitud AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.text())
            .then(html => {
                // Procesar la respuesta del servidor
                document.querySelector('.container-activo').innerHTML = html;

                // Ocultar el loader y mostrar el botón de envío nuevamente
                document.getElementById('loader').style.display = 'none';
                document.getElementById('submit-btn').style.display = 'inline';
            })
            .catch(error => {
                console.error('Error:', error);
                // Ocultar el loader y mostrar el botón de envío nuevamente en caso de error
                document.getElementById('loader').style.display = 'none';
                document.getElementById('submit-btn').style.display = 'inline';
            });
        });
    </script>
@endsection
