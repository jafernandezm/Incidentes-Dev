<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{

    public function index()
    {
        $docentes = Admin::with('user')->get();
        return view('docente.index', [
            'docentes' => $docentes
        ]);
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        // Crear el usuario
        $user = User::create([
            'name' => $input['nombre'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
        ])->assignRole($request->input('roles'));

        // Crear el docente y asociarlo con el usuario
        $docente = Tecnico::create([
            'user_id' => $user->id,
            'celular' => $input['celular'],
            'direccion' => $input['direccion'],
            'ci' => $input['ci'],
            //'especialidad' => $input['especialidad'],
        ]);

        return redirect()->route('docentes.index');
    }

    public function show(Tecnico $tecnico)
    {
        $tecnico->load('user');
        return view('docente.show', [
            'docente' => $tecnico
        ]);
    }

    public function edit(Tecnico $tecnico)
    {
        $tecnico->load('user');
        //role del docente


        return view('docente.edit', [
            'docente' => $tecnico
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        //
    }

    public function destroy(Admin $admin)
    {
        //
    }
}
