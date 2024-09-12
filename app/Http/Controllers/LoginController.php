<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LoginController extends Controller
{
    
    public function index(){
        return view('login');
    }

    public function loginVerify(Request $request){

        // echo request()->username;
        // echo request()->password;

        $request->validate([
            'password' => 'required|min:8'
        ], [
            'password.required' => 'El campo contraseña es obligatorio',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres'
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
            //return view('index'); 
            return redirect()->route('temas');
        }

        $user = User::where('username', $request->username)->first();
        return redirect()->back()->withErrors([
            'message' => $user ? 'Contraseña incorrecta' : 'Usuario incorrecto'
        ]);
    }
    
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }

}
