<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite(['resources/js/common.js', 'resources/css/app.css'])
	<style>
		.alert-error {
			background-color: #f8d7da; /* Fondo rojo claro */
			color: #721c24; /* Texto rojo oscuro */
			border: 1px solid #f5c6cb; /* Borde rojo */
			padding: 10px;
			margin-bottom: 15px;
			border-radius: 4px;
		}
		</style>
		
</head>

<body>

    <div class="container">
        <x-sidebar pagina="{{ $pagina }}"></x-sidebar>
        <div class="main">
            <x-header title={{$title}}></x-header>
            <div class="centro">
                @if(session('error'))
                        <div class="alert alert-error" id="error-message">
                            {{ session('error') }}
                        </div>
                    @endif
                {{ $slot }}
            </div>

        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.0/js/languages/es.js"></script>

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://kit.fontawesome.com/e342c8a50b.js" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Selecciona el mensaje de error
        var errorMessage = document.getElementById("error-message");
        if (errorMessage) {
            // Establece un temporizador para ocultar el mensaje después de 5 segundos
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 5000); // 5000 ms = 5 segundos
        }
    });
</script>
</html>
