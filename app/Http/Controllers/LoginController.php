<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/pos');
        }

      //  return view('auth.login');
      return response()->json(['message' => 'Mostrar formulario de inicio de sesión'], 200);
      
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contraseña' => 'required|string',
        ], [
            'usuario.required' => 'Ingresa tu usuario.',
            'contraseña.required' => 'Ingresa tu contraseña.',
        ]);

        // Verificar primero si el administrador existe pero su cuenta está desactivada
        $admin = Administrador::where('usuario', $request->usuario)->first();
        if ($admin && !$admin->activo) {
            return back()->withErrors(['status' => 'Esta cuenta se encuentra desactivada.'])->withInput();
        }

        // Credenciales con guard 'admin'
        // 'password' es la llave reservada por Laravel para invocar getAuthPassword() en el modelo
        $credenciales = [
            'usuario'  => $request->usuario,
            'password' => $request->contraseña,
            'activo'   => 1
        ];

        if (Auth::guard('admin')->attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended('/pos');
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas o cuenta inactiva.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();     

        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}
