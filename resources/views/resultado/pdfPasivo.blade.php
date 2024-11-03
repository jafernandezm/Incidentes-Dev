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
        .header, .table_data, .footer {
            width: 100%;
            margin-bottom: 20px;
        }
        .table_data {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        .table_data th, .table_data td {
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
            position: absolute;
            bottom: 10px;
            font-size: 10px;
        }
        .code-html, .code-javascript {
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
            <td>{{ $value }}</td>
        </tr>
        @endforeach
    </table>

    <!-- Resultados de escaneo -->
    <h3>Resultados del Escaneo</h3>
    @foreach ($resultados as $resultado)
        @php
            $data = json_decode($resultado->data, true);
        @endphp

        <table class="table_data">
            <tr>
                <th colspan="2">Resultado #{{ $loop->iteration }}</th>
            </tr>
            <tr>
                <td><strong>URL:</strong></td>
                <td>{{ $resultado->url ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Detalle:</strong></td>
                <td>{{ $resultado->detalle ?? 'N/A' }}</td>
            </tr>

            @if (is_array($data))
                @foreach ($data as $item)
                    <tr>
                        <td colspan="2"><strong>Detalle de Redirección</strong></td>
                    </tr>
                    <tr>
                        <td><strong>URL Origen:</strong></td>
                        <td>{{ $item['URL_ORIGEN'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipo:</strong></td>
                        <td>{{ $item['tipo'] ?? 'N/A' }}</td>
                    </tr>
                    @if (isset($item['redirecciones']))
                        <tr>
                            <td><strong>Redirecciones:</strong></td>
                            <td>
                                @foreach ($item['redirecciones'] as $redir)
                                    <p>{{ $redir }}</p>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </table>
    @endforeach

    <!-- Footer -->
    <footer class="footer">
        <p>Generado por Jhon Fernández - Contacto: contacto@tuempresa.com</p>
    </footer>
</body>

</html>
