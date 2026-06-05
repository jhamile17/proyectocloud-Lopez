@extends('layouts.admin')

@section('admin-content')
<h2 class="h5 mb-3">Editar categoría</h2>

<form action="{{ route('admin.categories.update', $category) }}" method="POST">
  @csrf @method('PUT')

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
  </div>

  <div class="d-flex justify-content-between">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Volver</a>
    <button class="btn btn-primary">Actualizar</button>
  </div>
</form>
@endsection
