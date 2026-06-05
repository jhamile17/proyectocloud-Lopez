@php
    use Livewire\Volt\Component;
    use Livewire\Attributes\Layout;
    use Illuminate\Support\Facades\Auth;

    new #[Layout('components.layouts.app')] class extends Component
    {
        public function resend()
        {
            if (! auth()->check()) {
                redirect()->route('login')->send();
                return;
            }

            auth()->user()->sendEmailVerificationNotification();
            session()->flash('status', 'Te enviamos un nuevo enlace. Revisa tu bandeja (y Spam/Promociones).');
        }

        public function logout()
        {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            redirect('/')->send();
        }
    };
@endphp

<div class="row g-4 align-items-stretch">
  {{-- Imagen izquierda --}}
  <div class="col-lg-7 d-none d-lg-block">
    <div class="h-100 rounded-3 overflow-hidden">
      <img src="{{ asset('images/auth-hero.jpg') }}"
           alt="Verifica tu correo - PROCAFES"
           class="w-100 h-100"
           style="object-fit: cover; min-height: 560px;">
    </div>
  </div>

  {{-- Mensaje + acciones --}}
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm h-100 rounded-4 border-0">
      <div class="card-body p-4 p-lg-5">
        <h2 class="h4 mb-2">¡Falta un pasito! ✉️</h2>
        <p class="text-muted">
          Te enviamos un correo de verificación a <strong>{{ auth()->user()->email }}</strong>.
          Abre el enlace para activar tu cuenta. Esto nos ayuda a mantener tu
          información segura y tu café a salvo.
        </p>

        @if (session('status'))
          <div class="alert alert-success" role="alert">{{ session('status') }}</div>
        @endif

        <div class="d-grid gap-2 mt-4">
          <button wire:click="resend" class="btn btn-procafes-dark" wire:loading.attr="disabled">
            Reenviar enlace
          </button>
          <button wire:click="logout" class="btn btn-light border">
            Cerrar sesión
          </button>
        </div>

        <p class="text-center text-muted mt-3 mb-0" style="font-size:.95rem;">
          Sugerencia: busca también en <em>Spam</em> o <em>Promociones</em>.  
          Si no llega en unos minutos, reenvíalo con el botón de arriba.
        </p>
      </div>
    </div>
  </div>
</div>
