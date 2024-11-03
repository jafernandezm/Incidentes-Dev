<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Escaneo</title>
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

        .header,
        .table_data,
        .footer {
            width: 100%;
            margin-bottom: 20px;
        }

        .table_data {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        .table_data th,
        .table_data td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table_data th {
            background-color: #4CAF50;
            color: white;
        }

        .footer {
            text-align: center;
            position: relative;
            bottom: 10px;
            font-size: 10px;
        }

        .code-html,
        .code-javascript {
            background-color: #f4f4f4;
            padding: 5px;
            border-radius: 4px;
            font-size: 10px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h2>Reporte de Escaneo</h2>
        <p><strong>Fecha:</strong> {{ date('Y-m-d') }}</p>
    </div>

    <!-- Información del escaneo -->
    <table class="table_data">
        <tr>
            <th colspan="2">Información del Escaneo</th>
        </tr>
        <tr>
            <td><strong>ID del Escaneo:</strong></td>
            <td>{{ $escaneo->id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>URL:</strong></td>
            <td>{{ $escaneo->url ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Tipo:</strong></td>
            <td>{{ $escaneo->tipo ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong></td>
            <td>{{ $escaneo->fecha ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Resultado:</strong></td>
            <td>{{ $escaneo->resultado ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Detalles adicionales -->
    <h3>Detalles del Escaneo</h3>
    <table class="table_data">
        @foreach ($detalles as $key => $value)
        <tr>
            <td><strong>{{ ucfirst($key) }}:</strong></td>
            <td>
                @if (is_array($value))
                <ul>
                    @foreach ($value as $item)
                    <li>
                        @if (is_array($item))
                        <ul>
                            @foreach ($item as $subItem)
                            <li>{{ $subItem }}</li>
                            @endforeach
                        </ul>
                        @else
                        {{ $item }}
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                {{ $value }}
                @endif
            </td>
        </tr>
        @endforeach
    </table>

    <h3>Resultados del Escaneo</h3>

    <!-- Inicializamos un contador para las URLs -->
    @php
    $totalUrls = 0;
    @endphp

    <!-- Generamos una tabla para todos los resultados -->
    <table class="table_data">
        <tr>
            <th>Resultado</th>
            <th>URLs JS Infectadas</th>
        </tr>

        @foreach ($resultados as $resultado)
        @php
            // Decodificamos el JSON en un array asociativo
            $data = json_decode($resultado->data, true);
            // Verificamos si hay URLs JS
            $urls_js = [];
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item['url_js'])) {
                        $urls_js[] = $item['url_js'];
                    }
                }
            }
            // Acumulamos el total de URLs
            $totalUrls += count($urls_js);
        @endphp

        <tr>
            <td>{{ $resultado->url ?? 'N/A' }}</td>
            <td>
                @if (count($urls_js) > 0)
                <ul>
                    @foreach ($urls_js as $url)
                    <li>{{ $url }}</li>
                    @endforeach
                </ul>
                @else
                No se encontraron URLs JS infectadas.
                @endif
            </td>
        </tr>
        @endforeach
    </table>

    @if ($totalUrls > 30)
    <h3>URLs JS Infectadas (continuación)</h3>
    <table class="table_data">
        <tr>
            <th>URLs JS Infectadas Adicionales</th>
        </tr>
        @foreach ($resultados as $resultado)
        @php
            // Decodificamos nuevamente para mostrar URLs adicionales
            $data = json_decode($resultado->data, true);
            $urls_js = [];
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item['url_js'])) {
                        $urls_js[] = $item['url_js'];
                    }
                }
            }
        @endphp

        @if (count($urls_js) > 30) <!-- Verificamos si hay más de 30 URLs JS -->
            @foreach ($urls_js as $index => $url)
                @if ($index >= 30) <!-- Mostrar solo las URLs que exceden las 30 iniciales -->
                <tr>
                    <td>{{ $url }}</td>
                </tr>
                @endif
            @endforeach
        @endif
        @endforeach
    </table>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <p>Generado por Jhon Fernández - Contacto: contacto@tuempresa.com</p>
    </footer>
</body>

</html>
