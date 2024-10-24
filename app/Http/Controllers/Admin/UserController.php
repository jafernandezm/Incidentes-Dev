<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $tecnico = Tecnico::with('user')->get();
        return view('admin.users', [
            'tecnicos' => $tecnico
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

        return redirect()->route('admin.users');
    }

    public function show(Tecnico $tecnico)
    {
        $tecnico->load('user');
        return view('admin.show', [
            'tecnico' => $tecnico
        ]);
    }

    public function edit(Tecnico $tecnico)
    {
        $tecnico->load('user');
        //role del docente


        return view('admin.edit', [
            'tecnico' => $tecnico
        ]);
    }

    public function update(Request $request, Tecnico $tecnico)
    {
        if(empty($request->get('password'))){
            $request['password'] = $tecnico->user->password;
        }

        if ($request->has('roles')) {
            $rolesIds = Role::whereIn('name', $request->input('roles'))->pluck('id');
            $tecnico->user->roles()->sync($rolesIds);
        } else {
            $tecnico->user->roles()->detach(); 
        }
    
        $tecnico->update($request->all());
    
        return redirect()->route('admin.users');
    }

    public function destroy(Tecnico $tecnico)
    {
        $tecnico->user->delete(); // Eliminar el usuario relacionado
        $tecnico->delete();

        return redirect()->route('admin.users');
    }

    public function mostrarPerfil(string $id)
    {
        $usuario = User::findOrFail($id);
        return view('perfil', compact('usuario'));
    }
    
    public function actualizarPerfil(Request $request, string $id)
    {
        // Validar los campos
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'apikey' => 'nullable|string|max:255', // Validar que la API Key sea opcional y de máximo 255 caracteres
        ]);
    
        // Buscar el usuario por ID
        $usuario = User::findOrFail($id);
    
        // Actualizar el nombre de usuario
        $usuario->username = $request->input('username');
    
        // Si el campo de contraseña está lleno, encriptar y actualizar la contraseña
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->input('password'));
        }
    
        // Si el campo de API Key está lleno, actualizar la API Key
        if ($request->filled('api_key')) {
            $usuario->api_key = $request->input('api_key');
        }
    
        // Guardar los cambios en la base de datos
        $usuario->save();
    
        // Redirigir al perfil actualizado
        return redirect()->route('perfil', ['id' => $usuario->id]);
    }
    
}