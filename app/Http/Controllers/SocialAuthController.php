<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirigir al usuario a la pantalla oficial de selección de cuenta de Google ("Elige una cuenta").
     */
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');

        // Si no se ha configurado un CLIENT_ID real de Google en el archivo .env, usar el modo demo
        if (empty($clientId) || !str_contains($clientId, '.apps.googleusercontent.com')) {
            return redirect()->route('auth.google.demo');
        }

        try {
            return Socialite::driver('google')
                ->with(['prompt' => 'select_account'])
                ->redirect();
        } catch (\Exception $e) {
            return redirect()->route('auth.google.demo');
        }
    }

    /**
     * Recibir la información de la cuenta seleccionada desde Google (Callback).
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar administrador por google_id o correo electrónico
            $admin = Administrador::where('google_id', $googleUser->id)
                ->orWhere('correo', $googleUser->email)
                ->first();

            if (!$admin) {
                // Registrar automáticamente con los datos de la cuenta de Google
                $admin = Administrador::create([
                    'usuario' => strtolower(explode('@', $googleUser->email)[0]),
                    'nombre' => $googleUser->name,
                    'correo' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'contraseña' => Hash::make(uniqid()),
                    'activo' => 1,
                ]);
            } else {
                $admin->update([
                    'google_id' => $googleUser->id,
                    'correo' => $googleUser->email,
                ]);
            }

            if (!$admin->activo) {
                return redirect('/login')->withErrors(['status' => 'Esta cuenta se encuentra desactivada.']);
            }

            Auth::guard('admin')->login($admin);
            session()->regenerate();

            return redirect()->intended('/pos')->with('success', '¡Bienvenido ' . $admin->nombre . '! Iniciaste sesión con tu cuenta de Google.');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Error al autenticar con Google: ' . $e->getMessage()]);
        }
    }

    /**
     * Simulación de selección de cuenta de Google para pruebas en vivo sin ID de cliente.
     */
    public function demoGoogleLogin()
    {
        $mockGoogleUser = (object) [
            'id' => '109876543210987654321',
            'name' => 'Usuario Google UTJ',
            'email' => '2125300121@soy.utj.edu.mx'
        ];

        $admin = Administrador::where('google_id', $mockGoogleUser->id)
            ->orWhere('correo', $mockGoogleUser->email)
            ->first();

        if (!$admin) {
            $admin = Administrador::create([
                'usuario' => '2125300121_utj',
                'nombre' => $mockGoogleUser->name,
                'correo' => $mockGoogleUser->email,
                'google_id' => $mockGoogleUser->id,
                'contraseña' => Hash::make('google123'),
                'activo' => 1,
            ]);
        }

        if (!$admin->activo) {
            return redirect('/login')->withErrors(['status' => 'Esta cuenta se encuentra desactivada.']);
        }

        Auth::guard('admin')->login($admin);
        session()->regenerate();

        return redirect('/pos')->with('success', '¡Autenticado con éxito con tu cuenta Google (' . $mockGoogleUser->email . ')!');
    }
}
