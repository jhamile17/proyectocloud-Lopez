@extends('layouts.app')

@section('title', 'Registro | PROCAFES')

@section('content')
<div class="container py-5" style="max-width: 480px;">
  <div class="card shadow-sm border-0">
    <div class="card-body p-4">
      <h4 class="mb-3 text-center fw-bold">Crear cuenta</h4>

      <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">Nombre completo</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
          @error('name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Correo electrónico</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Número de teléfono</label>
          <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
          @error('phone')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Dirección</label>
          <input type="text" name="address" value="{{ old('address') }}" class="form-control">
          @error('address')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Tipo de documento</label>
          <select name="document_type" class="form-select" required>
            <option value="dni" {{ old('document_type') == 'dni' ? 'selected' : '' }}>DNI</option>
            <option value="ruc" {{ old('document_type') == 'ruc' ? 'selected' : '' }}>RUC</option>
          </select>
          @error('document_type')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>


        <div class="mb-3">
          <label class="form-label fw-semibold">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
          @error('password')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Confirmar contraseña</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-procafes w-100">Registrar cuenta</button>
      </form>

      <hr class="my-4">

      <div class="text-center small">
        ¿Ya tienes una cuenta?
        <a href="{{ route('login') }}">Iniciar sesión</a>
      </div>

      <div class="text-center mt-3">
        <a href="{{ route('auth.google.redirect') }}" class="btn btn-outline-danger w-100">
          <i class="bi bi-google me-1"></i> Registrarse con Google
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
