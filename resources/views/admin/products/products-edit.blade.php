@extends('layouts.admin')
@section('title', 'Editar producto')

@section('admin-content')
<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar producto</h4>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf
        @method('PUT')

        {{-- Nombre y slug --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nombre</label>
          <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                 class="form-control @error('name') is-invalid @enderror">
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Precio, stock, estado --}}
        <div class="col-md-4">
          <label class="form-label fw-semibold">Precio (S/)</label>
          <input type="number" name="price" min="0" step="0.01"
                 value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror">
          @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Stock</label>
          <input type="number" name="stock" min="0"
                 value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror">
          @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Stock mínimo</label>
          <input type="number" name="stock_minimo" min="0"
                 value="{{ old('stock_minimo', $product->stock_minimo) }}" class="form-control @error('stock_minimo') is-invalid @enderror">
          @error('stock_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Estado</label>
          <select name="status" class="form-select">
            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Activo</option>
            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
          </select>
        </div>
        {{-- Categoría --}}
        <div class="mb-3">
          <label for="categories_id" class="form-label fw-semibold">Categoría</label>
          <select name="categories_id" id="categories_id" class="form-select" required>
            <option value="">Seleccionar categoría...</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->categories_id }}"
                {{ old('categories_id', $product->categories_id) == $cat->categories_id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
          @error('categories_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        
        {{-- Marca --}}
        <div class="mb-3">
          <label for="brand_id" class="form-label fw-semibold">Marca</label>
          <select name="brand_id" id="brand_id" class="form-select">
            <option value="">Sin marca</option>
            @foreach($brands as $b)
              <option value="{{ $b->brand_id }}"
                {{ old('brand_id', $product->brand_id) == $b->brand_id ? 'selected' : '' }}>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
          @error('brand_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        {{-- Imagen --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Imagen</label>
          <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
          @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

          {{-- Vista previa --}}
            <div class="mt-2">
                <img id="imagePreview"
                    src="{{ ($product->image && Storage::disk('public')->exists($product->image)) ? Storage::url($product->image) : 'https://via.placeholder.com/150x150?text=Sin+imagen' }}"
                    class="img-thumbnail" style="max-width:150px;">
             </div>

        {{-- Descripción --}}
        <div class="col-12">
          <label class="form-label fw-semibold">Descripción</label>
          <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        {{-- Botones --}}
        <div class="col-12 text-end mt-3">
          <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary px-4">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

{{-- Slug automático + vista previa imagen --}}
@section('scripts')
<script>
  // Generar slug automáticamente
  document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');
    if (nameInput && slugInput) {
      nameInput.addEventListener('input', function () {
        const slug = this.value
          .toLowerCase()
          .trim()
          .replace(/[^\w\s-]/g, '')
          .replace(/\s+/g, '-')
          .replace(/--+/g, '-');
        slugInput.value = slug;
      });
    }
  });

  // Vista previa de imagen
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
      const img = document.getElementById('imagePreview');
      img.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
@endsection
