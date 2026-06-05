@extends('layouts.admin')

@section('admin-content')
<h2 class="h5 mb-3">Nueva marca</h2>

<form action="{{ route('admin.brands.store') }}" method="POST">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
  </div>

  <div class="d-flex justify-content-between">
    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Cancelar</a>
    <button class="btn btn-primary">Guardar</button>
  </div>
</form>
@endsection
