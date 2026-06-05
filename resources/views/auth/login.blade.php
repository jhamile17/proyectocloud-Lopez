@extends('layouts.app')
@section('title', 'Iniciar sesión | PROCAFES')

@push('styles')
<style>
  /* Layout de 2 columnas que ocupa el alto de la ventana */
  .auth-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: 100vh;               /* ocupa toda la ventana */
  }

  /* Columna izquierda (imagen) */
  .auth-image {
    background:url("{{ asset('images/cafe_register.jpg') }}") center center/cover no-repeat;
  }

  .auth-pane {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    overflow: auto;              
  }

  .auth-card {
    width: 100%;
    max-width: 460px;
  }

  /* Ajuste visual cuando el header empuja el contenido hacia abajo */
  @media (min-width: 768px) {
    main.container-fluid, main.container, main {
      padding-top: 0 !important;
    }
  }

  /* Si tu navbar ocupa altura y quieres compensar, puedes usar: */
  /* .auth-wrap { height: calc(100vh - 64px); }  <-- si tu header es fijo de ~64px */
</style>
@endpush

@section('content')
<div class="auth-wrap">
  {{-- Izquierda: imagen a pantalla completa --}}
  <div class="auth-image d-none d-md-block"></div>

  {{-- Derecha: formulario centrado, sin scroll global --}}
  <div class="auth-pane">
    <div class="card shadow-sm border-0 auth-card">
      <div class="card-body p-4">
        <h4 class="mb-2 fw-bold">Iniciar sesión</h4>
        <p class="text-muted mb-4">Usa tu correo y contraseña para continuar.</p>

        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label class="form-label fw-semibold">Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Contraseña</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input type="checkbox" name="remember" class="form-check-input" id="remember">
              <label class="form-check-label" for="remember">Recordarme</label>
            </div>
            <a href="#" class="small text-muted">¿Olvidaste tu contraseña?</a>
          </div>

          <button type="submit" class="btn btn-procafes w-100">Ingresar</button>
        </form>

        <div class="text-center my-3 text-muted small">o</div>

        <a href="{{ route('auth.google.redirect') }}" class="btn btn-outline-danger w-100">
          <i class="bi bi-google me-1"></i> Continuar con Google
        </a>

        <div class="text-center mt-3 small">
          ¿No tienes cuenta?
          <a href="{{ route('register') }}">Regístrate</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection