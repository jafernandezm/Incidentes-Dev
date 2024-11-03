<!-- resources/views/resultado/pdfFiltracion.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Filtraciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            background-color: #fff;
        }
        .card-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .info-box {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #eee;
        }
        .entry {
            margin: 5px 0;
        }
        .entry-field {
            font-weight: bold;
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
                    <p class="info-title">Información adicional:</p>
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

</body>
</html>
