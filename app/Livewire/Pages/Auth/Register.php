<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';
    public string $address = '';
    public ?string $document_type = '';
    public ?string $document_number = null;

    protected function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','lowercase','email','max:255','unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => ['nullable','string','max:20'],
            'address' => ['nullable','string','max:255'],
            'document_type' => ['nullable','in:dni,ruc'],
            'document_number' => ['nullable','string','max:20','unique:users,document_number'],
        ];
    }

    // ANTES (causa el error)
// public function register(): \Symfony\Component\HttpFoundation\Response

// DESPUÉS (sin tipo de retorno)
public function register()
{
    $this->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','string','lowercase','email','max:255','unique:users,email'],
        'password' => ['required','confirmed', \Illuminate\Validation\Rules\Password::min(8)],
        'phone' => ['nullable','string','max:20'],
        'address' => ['nullable','string','max:255'],
        'document_type' => ['nullable','in:dni,ruc'],
        'document_number' => ['nullable','string','max:20'],
    ]);

    $user = \App\Models\User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => \Illuminate\Support\Facades\Hash::make($this->password),
        'phone' => $this->phone,
        'address' => $this->address,
        'document_type' => $this->document_type,
        'document_number' => $this->document_number,
    ]);

    \Illuminate\Support\Facades\Auth::login($user, remember: true);

    // Livewire soporta redirecciones retornando Redirector propio
    return redirect()->intended('/');
}


    public function render()
    {
        return view('livewire.pages.auth.register')
            ->title('Crear cuenta');
    }
}
