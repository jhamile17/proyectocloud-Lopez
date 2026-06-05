<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h4 class="mb-1">Iniciar sesión</h4>
          <p class="text-muted mb-4">Usa tu correo y contraseña para continuar.</p>

          {{-- Mensajes de error global --}}
          @if ($errors->any())
            <div class="alert alert-danger py-2">
              {{ $errors->first() }}
            </div>
          @endif

          <form wire:submit.prevent="login" class="vstack gap-3">
            <div>
              <label class="form-label">Correo electrónico</label>
              <input type="email"
                     class="form-control @error('state.email') is-invalid @enderror"
                     wire:model.defer="state.email"
                     placeholder="tu@correo.com" required>
              @error('state.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
              <label class="form-label">Contraseña</label>
              <input type="password"
                     class="form-control @error('state.password') is-invalid @enderror"
                     wire:model.defer="state.password"
                     placeholder="••••••••" required>
              @error('state.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember"
                       wire:model.defer="state.remember">
                <label class="form-check-label" for="remember">Recordarme</label>
              </div>

              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">¿Olvidaste tu contraseña?</a>
              @endif
            </div>

            <button class="btn btn-procafes-dark w-100" wire:loading.attr="disabled">
              <span wire:loading.remove>Ingresar</span>
              <span wire:loading>Ingresando…</span>
            </button>
          </form>

          {{-- Social (opcional) --}}
          @if (Route::has('auth.google.redirect'))
            <hr class="my-4">
            <a href="{{ route('auth.google.redirect') }}" class="btn btn-outline-secondary w-100">
              <i class="bi bi-google me-2"></i> Continuar con Google
            </a>
          @endif

          <p class="text-center mt-4 mb-0">
            ¿No tienes cuenta?
            @if (Route::has('register'))
              <a href="{{ route('register') }}">Regístrate</a>
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
