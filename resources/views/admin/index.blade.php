@extends('docentes_tab')

{{-- @vite(['resources/css/table_docen.css']) --}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-right">
                                <a href="{{ route('docentes.create') }}" class="btn_right"  data-placement="left">
                                    {{ ('Crear Docente') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <table class="tableR">
                            <thead>
                                <tr>
                                
                                    <th >Nombre</th>
                                    <th >Nombre de Usuario</th>
                                    <th >Rol</th>
                                    <th >Acciones</th>

                                </tr>
                            </thead>
                        
                            <tbody>
                                @foreach ($docentes as $docente)
                                
                                    <tr>
                                        
                                        <td >{{ $docente->user->name }}</td>
                                        <td >{{ $docente->user->username }}</td>
                                        <td >
                                            @foreach($docente->user->roles as $key => $rol)
                                            <span>{{ $rol->name }}{{ !$loop->last ? ' - ' : '' }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <form class="centered-form" action="{{ route('docentes.destroy', $docente->id) }}" method="POST">

                                                <a class="btn2  btn2-primary " href="{{ route('docentes.edit', $docente->id) }}" ><i class="fas fa-edit"></i></a>
                                                <a class="btn2  btn2-primary " href="{{ route('docentes.show', $docente->id) }}"><i class="fas fa-eye"></i></a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn2 btn-danger2  btn2-primary" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa-solid fa-trash-can"></i></button>
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