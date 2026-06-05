<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Schema;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Puedes agregar ->scopes(['openid','profile','email']) si lo deseas
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $g = Socialite::driver('google')->user();

            // Buscar usuario por email
            $user = User::where('email', $g->getEmail())->first();

            if (!$user) {
                $user = new User();
                // Mapea a tus columnas
                $user->name  = $g->getName() ?: ($g->user['given_name'] ?? 'Usuario');
                $user->email = $g->getEmail();
                $user->password = Hash::make(Str::random(32)); // password dummy
                // Campos opcionales en tu esquema:
                if (Schema::hasColumn($user->getTable(), 'role')) {
                    $user->role = 'customer';
                }
                if (Schema::hasColumn($user->getTable(), 'address')) {
                    $user->address = '';
                }
                if (Schema::hasColumn($user->getTable(), 'phone')) {
                    $user->phone = null;
                }
                // Si usas verificación por email y existe la columna:
                if (Schema::hasColumn($user->getTable(), 'email_verified_at')) {
                    $user->email_verified_at = now();
                }

                $user->save();
            }

            Auth::login($user, remember: true);

            // Redirige a donde intentaba ir o al home
            return redirect()->intended('/');
        } catch (\Throwable $e) {
            // Si algo falla, vuelve al login con mensaje
            return redirect()->route('login')->with('status', 'No se pudo iniciar con Google.');
        }
    }
}
