<style>
    .estado {
        display: inline-block;
        padding: 0.5rem 1rem;
        /* Aumenta el espacio interior */
        font-size: 1rem;
        /* Aumenta el tamaño de la fuente */
        font-weight: 700;
        /* Aumenta el peso de la fuente para negritas más notorias */
        border-radius: 9999px;
        /* Redondea los bordes */
        text-transform: uppercase;
        /* Opcional: convierte el texto a mayúsculas para mejor visibilidad */
    }

    /* Estilo para estado ACTIVO */
    .estado.activo {
        background-color: #34d399;
        /* Verde brillante */
        color: #064e3b;
        /* Texto verde oscuro */
    }

    /* Estilo para estado INACTIVO */
    .estado.PASIVO {
        background-color: #60a5fa;
        /* Azul brillante */
        color: #1e40af;
        /* Texto azul oscuro */
    }

    /* Estilo para estado FILTRACION */
    .estado.FILTRACIONES {
        background-color: #f87171;
        /* Rojo brillante */
        color: #7f1d1d;
        /* Texto rojo oscuro */
    }

    /* Estilos generales para la celda de la tabla */
    td {
        padding: 8px;
        text-align: center;
    }

    /* Contenedor de botones */
/* Contenedor de botones */
.button-container {
    display: flex; 
    justify-content: center; 
    gap: 12px; /* Espacio entre los botones */
}

/* Botón genérico */
.button {
    display: inline-block;
    padding: 10px 20px; /* Aumenta el padding para más espacio */
    font-size: 16px; /* Tamaño de fuente más grande */
    font-weight: 600;
    color: white;
    border: none;
    border-radius: 8px; /* Bordes más redondeados */
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease; /* Efectos de transición */
    min-width: 80px;
}

/* Botón "Ver" */
.ver-boton {
    background-color: #4CAF50; /* Verde */
}

.ver-boton:hover {
    background-color: #45a049; /* Verde oscuro */
    transform: scale(1.05); /* Efecto de escala en hover */
}

/* Botón "PDF" */
.pdf-boton {
    background-color: #2196F3; /* Azul */
}

.pdf-boton:hover {
    background-color: #1e87d9; /* Azul más oscuro */
    transform: scale(1.05); /* Efecto de escala en hover */
}

/* Estilo para el botón que abre el modal */
.ver-reporte {
    padding: 10px 20px; /* Espaciado interno */
    font-size: 16px; /* Tamaño de fuente */
    font-weight: bold;
    border-radius: 8px; /* Bordes redondeados */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Transiciones suaves */
    min-width: 120px; /* Ancho mínimo */
}

/* Estilos para diferentes estados del botón */
.reportar-boton {
    background-color: #f44336; /* Rojo */
}

.no-reportar-boton {
    background-color: #2196F3; /* Azul */
}

.procesar-boton {
    background-color: #FFEB3B; /* Amarillo */
}

.ver-reporte:hover,
.reportar-boton:hover,
.no-reportar-boton:hover,
.procesar-boton:hover {
    opacity: 0.8; /* Reducción de opacidad al pasar el mouse */
    transform: scale(1.05); /* Efecto de escala en hover */
}

/* Diseño responsivo */
@media (max-width: 600px) {
    .button {
        padding: 8px 12px; /* Ajusta el padding para pantallas pequeñas */
        font-size: 14px; /* Tamaño de fuente más pequeño */
        min-width: 60px; /* Ajusta el ancho mínimo */
    }
}

/* Efecto del modal */
.modalmask {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 99999;
    opacity: 0;
    transition: opacity 400ms ease-in;
    pointer-events: none;
    display: flex;
    align-items: center; 
    justify-content: center; 
}

.modalmask:target {
    opacity: 1;
    pointer-events: auto;
}

/* Formato de la ventana */
.modalbox {
    width: 400px;
    padding: 20px;
    background: #fff;
    border-radius: 8px; /* Bordes más redondeados */
}

/* Botón de cerrar */
.close {
    background: #606061;
    color: #FFFFFF;
    line-height: 25px;
    position: absolute;
    right: 10px;
    top: 10px;
    width: 24px;
    text-align: center;
    text-decoration: none;
    font-weight: bold;
    border-radius: 3px;
}

.close:hover {
    background: #FAAC58;
    color: #222;
}

</style>

@vite(['resources/css/table_docen.css'])
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                {{-- <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-right">
                            <a href="{{ route('admin.create') }}" class="btn_right" data-placement="left">
                                {{ 'Crear Usuario' }}
                            </a>
                        </div>
                    </div>
                </div> --}}

                <div class="card-body bg-white">
                    <table class="tableR">
                        <thead>
                            <tr>

                                <th>Nombre</th>
                                <th>Tipo Analisis</th>
                                <th>Fecha</th>
                                <th>Analisis</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($escaneos as $escaneo)
                                <tr>

                                    <td>{{ $escaneo['url'] }}</td>
                                    <td>
                                        <span class="estado {{ strtolower($escaneo['tipo']) }}">
                                            {{ $escaneo['tipo'] }}
                                        </span>

                                    </td>
                                    <td>
                                        {{ $escaneo['fecha'] }}
                                    </td>
                                    <td>
                                        {{ $escaneo['resultado'] }}
                                    </td>
                                    <td>
                                        <div class="button-container">
                                            <a href="{{ route('escaneo.enviar', ['id' => $escaneo['id']]) }}" class="ver-boton button">VER</a>
                                    
                                            <form action="{{ route('escaneo.pdf', ['id' => $escaneo['id']]) }}" method="GET" style="display: inline;">
                                                <button type="submit" class="pdf-boton button">PDF</button>
                                            </form>
                                    
                                            <a href="#reportModal" class="ver-reporte button 
                                                @if ($escaneo['estado'] === 'reportar') reportar-boton 
                                                @elseif ($escaneo['estado'] === 'no reportar') no-reportar-boton 
                                                @elseif ($escaneo['estado'] === 'procesar') procesar-boton @endif">
                                                {{ ucfirst($escaneo['estado']) }}
                                            </a>
                                    
                                            <!-- Modal -->
                                            <div id="reportModal" class="modalmask">
                                                <div class="modalbox movedown">
                                                    <a href="#close" title="Close" class="close">X</a>
                                                    <h2>Selecciona una opción</h2>
                                                    <p>¿Qué deseas hacer con este escaneo?</p>
                                                    <form id="reportForm" action="{{ route('escaneo.reportarEstado') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="escaneo_id" id="escaneo_id" value="{{ $escaneo['id'] }}">
                                                        <button type="submit" name="estado" value="reportar" class="reportar-boton button">Reportar</button>
                                                        <button type="submit" name="estado" value="no reportar" class="no-reportar-boton button">No Reportar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- <td>
                                            <form class="centered-form" action="{{ route('admin.destroy', $tecnico->id) }}" method="POST">

                                                <a class="btn2  btn2-primary " href="{{ route('admin.edit', $tecnico->id) }}" ><i class="fas fa-edit"></i></a>
                                                <a class="btn2  btn2-primary " href="{{ route('admin.show', $tecnico->id) }}"><i class="fas fa-eye"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn2 btn-danger2  btn2-primary" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->



</div>
<script>
    function openModal(escaneoId) {
        document.getElementById('escaneo_id').value = escaneoId;
        $('#reportModal').modal('show'); // Abre el modal
    }
</script>
