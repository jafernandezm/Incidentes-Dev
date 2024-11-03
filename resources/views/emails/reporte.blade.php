<!-- resources/views/emails/reporte_incidente.blade.php -->
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
            
            <li><strong>URL</strong> {{ $resultados['url'] }}</li>
        </ul>
        <p>Para más información, haga clic en el botón a continuación:</p>
        <a href="{{ url('/escaneo/enviar/' . $escaneoResultado->id) }}" class="button">Ver más detalles</a>
    </div>
</body>
</html>
