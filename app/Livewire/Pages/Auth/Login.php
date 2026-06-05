<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

class Login extends Component
{
    public array $state = [
        'email'    => '',
        'password' => '',
        'remember' => false,
    ];

    public function mount()
    {
        // Si ya está autenticado, lo redirige según su rol
        if (Auth::check()) {
            return $this->afterLoginRedirect();
        }
    }

    public function login()
    {
        $this->validate([
            'state.email'    => ['required', 'email'],
            'state.password' => ['required', 'string'],
        ]);

        $remember = (bool) ($this->state['remember'] ?? false);

        if (! Auth::attempt([
            'email'    => $this->state['email'],
            'password' => $this->state['password'],
        ], $remember)) {
            throw ValidationException::withMessages([
                'state.email' => __('auth.failed'),
            ]);
        }

        session()->regenerate();

        return $this->afterLoginRedirect();
    }

    /**
     * Redirige según el rol o contexto
     */
    private function afterLoginRedirect()
    {
        $user = Auth::user();

        // 1️⃣ ADMIN → Dashboard administrativo
        $isAdmin = method_exists($user, 'isAdmin')
            ? $user->isAdmin()
            : (($user->role ?? null) === 'admin');

        if ($isAdmin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2️⃣ CLIENTE → Productos (home)
        $hasIntended = session()->has('url.intended');
        $cartCount   = (int) (session('cart.count') ?? 0);

        // Si venía de una ruta protegida (como checkout), respétalo
        if ($hasIntended) {
            return redirect()->intended(route('home'));
        }

        // Si tenía carrito, lo manda a pagar
        if ($cartCount > 0 && Route::has('checkout')) {
            return redirect()->route('checkout');
        }

        // Por defecto, cliente → vista de productos
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.pages.auth.login')->layout('layouts.app');
    }
}
