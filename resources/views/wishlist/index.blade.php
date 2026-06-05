@extends('layouts.app')
@section('title', 'Mi lista de deseos')

@section('content')
@php use Illuminate\Support\Facades\Storage; @endphp

<div class="container py-4">
  <h3 class="mb-4">💖 Mi lista de deseos</h3>

  @if($items->isEmpty())
    <div class="alert alert-secondary text-center shadow-sm">
      <i class="bi bi-heart text-danger me-2"></i> No tienes productos en tu lista de deseos aún.
    </div>
  @else
    <div class="row g-3">
      @foreach($items as $item)
        @php $product = $item->product; @endphp
        @if($product)
          <div class="col-6 col-md-4 col-lg-3 js-wishlist-card" data-product="{{ $product->id }}">
            <div class="card h-100 shadow-sm border-0">
              <div class="ratio ratio-1x1 bg-light">
                @php
                  $img = ($product->image && Storage::disk('public')->exists($product->image))
                          ? Storage::url($product->image)
                          : 'https://via.placeholder.com/400x400?text=Producto';
                @endphp
                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover rounded-top">
              </div>

              <div class="card-body text-center">
                <h6 class="card-title mb-1">{{ $product->name }}</h6>
                <p class="text-muted small mb-2">{{ $product->category->name ?? 'Sin categoría' }}</p>
                <strong class="d-block mb-3">S/ {{ number_format($product->price, 2) }}</strong>
              </div>

              <div class="card-footer bg-white border-0 d-flex justify-content-center gap-2 pb-3">
                {{-- Quitar (toggle) --}}
                <form class="js-wishlist-toggle" data-product="{{ $product->id }}">
                  @csrf
                  <button class="btn btn-outline-danger btn-sm" title="Quitar de la lista">
                    <i class="bi bi-heart-fill"></i>
                  </button>
                </form>

                {{-- Agregar al carrito --}}
                <button type="button" class="btn btn-warning btn-sm btn-add-to-cart"
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        data-price="{{ $product->price }}"
                        data-image="{{ $img }}"
                        data-url="{{ url('/product/'.$product->id) }}">
                  <i class="bi bi-cart-plus me-1"></i> Añadir al carrito
                </button>
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  @endif
</div>
@endsection
