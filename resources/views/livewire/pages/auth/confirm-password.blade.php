@php
    use Livewire\Volt\Component;
    use Livewire\Attributes\Layout;
    use Illuminate\Support\Facades\Hash;

    new #[Layout('components.layouts.app')] class extends Component
    {
        public array $state = ['password' => ''];

        public function confirm()
        {
            $data = validator($this->state, [
                'password' => ['required'],
            ])->validate();

            if (! Hash::check($data['password'], auth()->user()->password)) {
                $this->addError('password', 'La contraseña no coincide.');
                return;
            }

            // Marca la confirmación reciente de contraseña (como hace Laravel)
            session()->put('auth.password_confirmed_at', time());

            // Continúa a la acción protegida o vuelve al dashboard/home
            redirect()->intended('/')->send();
        }
    };
@endphp

<div class="row g-4 align-items-stretch">
  {{-- Imagen izquierda --}}
  <div class="col-lg-7 d-none d-lg-block">
    <div class="h-100 rounded-3 overflow-hidden">
      <img src="{{ asset('images/auth-hero.jpg') }}"
           alt="Confirmar contraseña - PROCAFES"
           class="w-100 h-100"
           style="object-fit: cover; min-height: 560px;">
    </div>
  </div>

  {{-- Form derecha --}}
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm h-100 rounded-4 border-0">
      <div class="card-body p-4 p-lg-5">
        <h2 class="h4 mb-1">Confirma tu contraseña</h2>
        <p class="text-muted mb-4">Por seguridad, vuelve a escribir tu contraseña.</p>

        <form wire:submit="confirm" novalidate>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" type="password"
                   wire:model.defer="state.password" required autocomplete="current-password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-procafes-dark btn-lg" wire:loading.attr="disabled">
              Continuar
            </button>
          </div>
        </form>

        <p class="text-center mt-3 mb-0">
          <a href="{{ route('password.request') }}" class="link-procafes text-decoration-none">
            ¿Olvidaste tu contraseña?
          </a>
        </p>
      </div>
    </div>
  </div>
</div>
