@extends('usuarios')

@vite(['resources/css/table_docen.css']) 

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="float-right">
                                <a href="{{ route('admin.create') }}" class="btn_right"  data-placement="left">
                                    {{ ('Crear Usuario') }}
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
                                @foreach ($tecnicos as $tecnico)
                                
                                    <tr>
                                        
                                        <td >{{ $tecnico->user->name }}</td>
                                        <td >{{ $tecnico->user->username }}</td>
                                        <td >
                                            @foreach($tecnico->user->roles as $key => $rol)
                                            <span>{{ $rol->name }}{{ !$loop->last ? ' - ' : '' }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <form class="centered-form" action="{{ route('admin.destroy', $tecnico->id) }}" method="POST">

                                                <a class="btn2  btn2-primary " href="{{ route('admin.edit', $tecnico->id) }}" ><i class="fas fa-edit"></i></a>
                                                <!-- <a class="btn2  btn2-primary " href="{{ route('admin.show', $tecnico->id) }}"><i class="fas fa-eye"></i></a> -->
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