<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Filtraciones</title>
    <style>
        /* Estilos generales */
        * {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            padding: 20px;
        }
        .header, .grid-container, .card, .info-box, .footer {
            width: 100%;
            margin-bottom: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-title {
            font-size: 14px;
            margin-bottom: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 5px;
            border-radius: 3px;
        }
        .info-box {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 3px;
            background-color: #f9f9f9;
        }
        .entry {
            margin: 5px 0;
        }
        .entry-field {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            position: relative;
            bottom: 10px;
            font-size: 10px;
        }
    </style>
</head>
<body>

<h1>Reporte de Filtraciones</h1>
@php
    $filtracionesAgrupadas = collect($datosFiltrados)->groupBy('filtracion');
@endphp

<div class="grid-container">
    @foreach ($filtracionesAgrupadas as $filtracion => $datos)
        <div class="card">
            <h3 class="card-title">Filtración: {{ $filtracion }}</h3>

            @if (!empty($datos[0]['informacion']))
                <div class="info-box">
                    <p class="info-title"><strong>Información adicional:</strong></p>
                    <p class="info-content">{{ $datos[0]['informacion'] }}</p>
                </div>
            @endif

            @foreach ($datos as $result)
                <div class="info-box">
                    @php
                        $data = json_decode($result['data'], true);
                    @endphp

                    @if (!empty($data))
                        @foreach ($data as $key => $value)
                            <div class="entry">
                                <p class="entry-field">{{ ucfirst($key) }}: <span>{{ $value }}</span></p>
                            </div>
                        @endforeach
                    @else
                        <p>No hay datos disponibles.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<!-- Footer -->
<footer class="footer">
    <p>Generado por Jhon Fernández - Contacto: contacto@tuempresa.com</p>
</footer>

</body>
</html>
