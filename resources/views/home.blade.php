@extends('layouts.app')
@section('title','Inicio | PROCAFES')

@section('content')
@php
  use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Str;
@endphp

<div class="container-fluid">
  <div class="row g-3">

    {{-- Sidebar filtros --}}
    <aside class="col-12 col-lg-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="mb-3">Filtrar</h5>

          <form method="GET" action="{{ route('home') }}" class="vstack gap-3">

            {{-- Buscar --}}
            <div>
              <label class="form-label">Buscar</label>
              <input
                type="text"
                name="q"
                value="{{ request('q','') }}"
                placeholder="Café, molido, etc."
                class="form-control"
              >
            </div>

            {{-- Categoría --}}
            <div>
              <label class="form-label">Categoría</label>
              <select name="category" class="form-select">
                <option value="">Todas</option>
                @foreach($categories as $c)
                  <option value="{{ $c->id }}" @selected((string)request('category') === (string)$c->id)>
                    {{ $c->name }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Marca --}}
            <div>
              <label class="form-label">Marca</label>
              <select name="brand" class="form-select">
                <option value="">Todas</option>
                @foreach($brands as $b)
                  <option value="{{ $b->id }}" @selected((string)request('brand') === (string)$b->id)>
                    {{ $b->name }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Precio --}}
            <div class="row g-2">
              <div class="col">
                <label class="form-label">Min (S/)</label>
                <input type="number" step="0.01" min="0" name="min" value="{{ request('min') }}" class="form-control">
              </div>
              <div class="col">
                <label class="form-label">Max (S/)</label>
                <input type="number" step="0.01" min="0" name="max" value="{{ request('max') }}" class="form-control">
              </div>
            </div>

            {{-- Orden --}}
            <div>
              <label class="form-label">Ordenar por</label>
              <select name="sort" class="form-select">
                <option value="new"        @selected(request('sort','new')==='new')>Nuevos primero</option>
                <option value="price_asc"  @selected(request('sort')==='price_asc')>Precio: menor a mayor</option>
                <option value="price_desc" @selected(request('sort')==='price_desc')>Precio: mayor a menor</option>
              </select>
            </div>

            <div class="d-grid gap-2 mt-1">
              <button class="btn btn-procafes-dark">Aplicar filtros</button>
              <a href="{{ route('home') }}" class="btn btn-light border">Limpiar</a>
            </div>
          </form>
        </div>
      </div>
    </aside>

    {{-- Listado de productos --}}
    <section class="col-12 col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0">Productos</h4>
        <small class="text-muted">{{ $products->total() }} resultados</small>
      </div>

      @if(!$products->count())
        <div class="alert alert-info">No se encontraron productos con los filtros seleccionados.</div>
      @else
        <div class="row g-3">
          @foreach($products as $p)
            <div class="col-6 col-md-4">
              <div class="card h-100 shadow-sm border-0">

                {{-- Imagen --}}
                <div class="ratio ratio-1x1 bg-light">
                  @if($p->image && Storage::disk('public')->exists($p->image))
                    <img src="{{ Storage::url($p->image) }}" alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover">
                  @else
                    <img src="https://via.placeholder.com/600x600?text=Producto" alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover">
                  @endif
                </div>

                {{-- Info --}}
                <div class="card-body">
                  <div class="small text-muted mb-1">
                    {{ $p->category->name ?? '—' }} @if($p->brand) • {{ $p->brand->name }} @endif
                  </div>
                  <h6 class="card-title mb-1" title="{{ $p->name }}">{{ Str::limit($p->name, 50) }}</h6>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">S/ {{ number_format($p->price, 2) }}</span>
                    <span class="badge {{ $p->stock > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">
                      {{ $p->stock > 0 ? 'Stock: '.$p->stock : 'Sin stock' }}
                    </span>
                  </div>
                </div>

                {{-- Acciones --}}
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                  <div class="vstack gap-2">

                    {{-- Agregar al carrito (AJAX / sin login) --}}
                    <button
                      type="button"
                      class="btn btn-procafes-accent w-100 btn-add-to-cart"
                      data-id="{{ $p->id }}"
                      data-name="{{ $p->name }}"
                      data-price="{{ $p->price }}"
                      data-image="{{ $p->image ? Storage::url($p->image) : 'https://via.placeholder.com/600x600?text=Producto' }}"
                      data-url="#"
                      {{ $p->stock > 0 ? '' : 'disabled' }}
                    >
                      <i class="bi bi-cart-plus me-1"></i> Agregar al carrito
                    </button>

                    {{-- Wishlist (toggle via AJAX) --}}
                    @auth
                      <form class="js-wishlist-toggle d-grid" data-product="{{ $p->id }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                          <i class="bi bi-heart me-1"></i>
                          <span class="js-wl-text">Añadir a favoritos</span>
                        </button>
                      </form>
                    @else
                      <a href="{{ route('login') }}" class="btn btn-outline-danger w-100">
                        <i class="bi bi-heart me-1"></i> Añadir a favoritos
                      </a>
                    @endauth

                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-3">
          {{ $products->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </section>
  </div>
</div>
@endsection
