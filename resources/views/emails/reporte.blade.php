<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Incidente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-wrap: break-word; /* Evita que el texto se salga del contenedor */
            word-wrap: break-word; /* Compatibilidad con navegadores antiguos */
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }
        p {
            color: #555;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        ul {
            color: #555;
            padding-left: 20px;
            margin-bottom: 20px;
        }
        li {
            margin-bottom: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .resultado {
            margin-left: 20px;
        }
        .scrollable {
            max-height: 150px; /* Altura máxima para el contenido desplazable */
            overflow-y: auto; /* Hace que el contenido sea desplazable verticalmente */
            border: 1px solid #ddd; /* Borde para distinguir el área desplazable */
            padding: 10px;
            background-color: #f9f9f9;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles del Incidente</h1>
        <p>Se ha detectado un nuevo incidente en el sistema. A continuación, se detallan los datos relevantes:</p>
        <ul>
            <li><strong>Fecha:</strong> {{ $escaneoResultado->fecha }}</li>
            <li><strong>Tipo:</strong> {{ $escaneoResultado->tipo }}</li>
            <li><strong>Estado:</strong> {{ $escaneoResultado->estado }}</li>
            <li><strong>Consulta:</strong> {{ $escaneoResultado->url }}</li>
        </ul>

        <h2>Resultados del Incidente</h2>

        {{-- Recorremos los fragmentos de resultados divididos --}}
        @foreach($resultadosDivididos as $resultados)
            <h3>Parte de los Resultados</h3>
            <ul>
                @foreach($resultados as $item)
                    <li>
                        <strong>URL:</strong> {{ $item->url }}<br>
                        <strong>Detalle:</strong> {{ $item->detalle }}<br>
                        <strong>Datos:</strong>
                        <ul>
                            @php
                                $data = json_decode($item->data, true);
                                if (is_array($data)) {
                                    foreach ($data as $index => $dataItem) {
                                        echo "<li class='resultado'><strong>Resultado " . ($index + 1) . ":</strong></li>";
                                        echo "<ul class='resultado'>";
                                        foreach ($dataItem as $key => $value) {
                                            // Convertir el valor a string si es un array
                                            if (is_array($value)) {
                                                $value = json_encode($value); // Convertir array a JSON
                                            }
                                            // Manejo especial para los campos "html" y "html_infectado"
                                            if ($key === 'html' || $key === 'html_infectado') {
                                                $lines = explode("\n", $value); // Dividir en líneas
                                                $lines = array_slice($lines, 0, 20); // Limitar a 20 líneas
                                                $value = implode("\n", $lines); // Unir las líneas
                                                $value = substr($value, 0, 300) . '...'; // Limitar a 300 caracteres
                                                echo "<li><strong>" . ucfirst($key) . ":</strong></li>";
                                                echo "<div class='scrollable'>" . nl2br(e($value)) . "</div>";
                                            } else {
                                                // Mostrar otros campos normalmente
                                                echo "<li><strong>" . ucfirst($key) . ":</strong> " . nl2br(e($value)) . "</li>";
                                            }
                                        }
                                        echo "</ul>";
                                    }
                                }
                            @endphp
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endforeach
        
        <p>Para más información, haga clic en el botón a continuación:</p>
        <a href="{{ url('/escaneo/enviar/' . $escaneoResultado->id) }}" class="button">Ver más detalles</a>
    </div>
</body>
</html>