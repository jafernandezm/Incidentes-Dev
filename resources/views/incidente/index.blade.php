@extends('incidente')

@vite(['resources/css/table_docen.css'])
{{-- {{dd($incidentes) }} --}}
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-right">
                                <a href="{{ route('incidente.create') }}" class="btn_right" data-placement="left">
                                    {{ 'Crear  Incidente' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <table class="tableR">
                            <thead>
                                <tr>

                                    <th>Tipo Incidente</th>
                                    <th>Descripcion</th>
                                    <th>Contenido</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($incidentes as $incidente)
                                    <tr>

                                        <!-- Aquí mostramos el nombre del tipo en lugar del ID -->
                                        <td> {{$incidente->tipo_id;}}:{{  $incidente->tipo->nombre }}</td>
                                        <td>
                                            {{ $incidente->descripcion }}
                                        </td>
                                        <td>{{ $incidente->contenido }}</td>
                                        <td>
                                            {{ $incidente->fecha }}
                                        </td>
                                        <td>
                                            <form class="centered-form"
                                                action="{{ route('incidente.destroy', $incidente->id) }}" method="POST">

                                                <a class="btn2  btn2-primary "
                                                    href="{{ route('incidente.edit', $incidente->id) }}"><i
                                                        class="fas fa-edit"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn2 btn-danger2  btn2-primary"
                                                    onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
