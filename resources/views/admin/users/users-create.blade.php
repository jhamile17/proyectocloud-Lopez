@extends('layouts.admin')

@section('admin-content')
<h2 class="h5 mb-3">Crear cliente</h2>

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('admin.users.store') }}" method="POST">
  @csrf

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
      @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Correo electrónico</label>
      <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
      @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Teléfono</label>
      <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Tipo de documento</label>
      <select name="document_type" class="form-control" required>
        <option value="dni">DNI</option>
        <option value="ce">Carnet de extranjería</option>
      </select>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Número de documento</label>
      <input type="text" name="document_number" 
          value="{{ old('document_number') }}"
          class="form-control @error('document_number') is-invalid @enderror"
          id="docNumber" required>

        @error('document_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Dirección</label>
      <input type="text" name="address" value="{{ old('address') }}" class="form-control">
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
      @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Confirmar Contraseña</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>
  </div>

  <div class="col-md-6 mb-3">
      <label class="form-label">Rol del usuario</label>
      <select name="role" class="form-control" required>
        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Cliente</option>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
      </select>
      @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

  <div class="d-flex justify-content-between">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver</a>
    <button class="btn btn-primary">Crear Usuario</button>
  </div>
</form>
@endsection