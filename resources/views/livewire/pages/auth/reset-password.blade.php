@php
    use Livewire\Volt\Component;
    use Livewire\Attributes\Layout;
    use Illuminate\Support\Facades\Password;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;
    use Illuminate\Auth\Events\PasswordReset;

    new #[Layout('components.layouts.app')] class extends Component
    {
        public array $state = [
            'token' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        public function mount()
        {
            $this->state['token'] = request()->route('token');      // /reset-password/{token}
            $this->state['email'] = request()->input('email', '');  // ?email=...
        }

        public function resetPassword()
        {
            $data = validator($this->state, [
                'token' => ['required'],
                'email' => ['required','email','exists:users,email'],
                'password' => ['required','min:8','same:password_confirmation'],
                'password_confirmation' => ['required'],
            ])->validate();

            $status = Password::reset(
                [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'password_confirmation' => $data['password_confirmation'],
                    'token' => $data['token'],
                ],
                function ($user) use ($data) {
                    $user->forceFill(['password' => Hash::make($data['password'])])->save();
                    $user->setRememberToken(Str::random(60));
                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                redirect()->route('login')->with('status', __($status))->send();
                return;
            }

            session()->flash('status', __($status));
        }
    };
@endphp

<div class="row g-4 align-items-stretch">
  {{-- Imagen izquierda --}}
  <div class="col-lg-7 d-none d-lg-block">
    <div class="h-100 rounded-3 overflow-hidden">
      <img src="{{ asset('images/auth-hero.jpg') }}"
           alt="Restablecer contraseña - PROCAFES"
           class="w-100 h-100"
           style="object-fit: cover; min-height: 560px;">
    </div>
  </div>

  {{-- Form derecha --}}
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm h-100 rounded-4 border-0">
      <div class="card-body p-4 p-lg-5">
        <h2 class="h4 mb-1">Restablecer contraseña</h2>
        <p class="text-muted mb-4">Ingresa una nueva contraseña para tu cuenta.</p>

        @if (session('status'))
          <div class="alert alert-info" role="alert">{{ session('status') }}</div>
        @endif

        <form wire:submit="resetPassword" novalidate>
          <input type="hidden" wire:model="state.token">

          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email"
                   wire:model.defer="state.email"
                   required autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="tucorreo@dominio.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input id="password" type="password"
                   wire:model.defer="state.password" required autocomplete="new-password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <input id="password_confirmation" type="password"
                   wire:model.defer="state.password_confirmation" required autocomplete="new-password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="••••••••">
            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-procafes-dark btn-lg" wire:loading.attr="disabled">
              Guardar nueva contraseña
            </button>
          </div>
        </form>

        <p class="text-center mt-3 mb-0">
          <a href="{{ route('login') }}" class="link-procafes text-decoration-none">Volver a iniciar sesión</a>
        </p>
      </div>
    </div>
  </div>
</div>
