<style>
    .estado {
        display: inline-block;
        padding: 0.5rem 1rem; /* Aumenta el espacio interior */
        font-size: 1rem; /* Aumenta el tamaño de la fuente */
        font-weight: 700; /* Aumenta el peso de la fuente para negritas más notorias */
        border-radius: 9999px; /* Redondea los bordes */
        text-transform: uppercase; /* Opcional: convierte el texto a mayúsculas para mejor visibilidad */
    }
    /* Estilo para estado ACTIVO */
    .estado.activo {
        background-color: #34d399; /* Verde brillante */
        color: #064e3b; /* Texto verde oscuro */
    }

    /* Estilo para estado INACTIVO */
    .estado.PASIVO {
        background-color: #60a5fa; /* Azul brillante */
        color: #1e40af; /* Texto azul oscuro */
    }

    /* Estilo para estado FILTRACION */
    .estado.FILTRACIONES {
        background-color: #f87171; /* Rojo brillante */
        color: #7f1d1d; /* Texto rojo oscuro */
    }
    .view-escaneos a{
        width: max-content;
        color: var(--blue-strong);
        text-decoration: none;
    }
</style>

@vite(['resources/css/table_docen.css']) 
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
    

                    <div class="card-body bg-white">
                        <table class="tableR">
                            <thead>
                                <tr>
                                
                                    <th >Nombre</th>
                                    <th >Tipo Analisis</th>
                                    <th >Fecha</th>
                                    <th >Maliciosos</th>
                                    <th >Acciones</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                @foreach ($escaneos as $escaneo)
                                
                                    <tr>
                                        
                                        <td >{{  $escaneo['url'] }}</td>
                                        <td >
                                            <span class="estado {{ strtolower($escaneo['tipo']) }}">
                                                {{ $escaneo['tipo'] }}
                                            </span>
                                            
                                        </td>
                                        <td>
                                            {{ $escaneo['fecha'] }}
                                        </td>
                                        <td >
                                            {{ $escaneo['resultado'] }}
                                        </td>
                                        <td class="view-escaneos">
                                            <a href="{{ route('escaneo.enviar', ['id' => $escaneo['id']]) }}"><i class="fa-solid fa-eye"></i></a>
                                        </td>
                                        <!-- {{-- <td>
                                            <form class="centered-form" action="{{ route('admin.destroy', $tecnico->id) }}" method="POST">

                                                <a class="btn2  btn2-primary " href="{{ route('admin.edit', $tecnico->id) }}" ><i class="fas fa-edit"></i></a>
                                                <a class="btn2  btn2-primary " href="{{ route('admin.show', $tecnico->id) }}"><i class="fas fa-eye"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn2 btn-danger2  btn2-primary" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td> --}} -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
