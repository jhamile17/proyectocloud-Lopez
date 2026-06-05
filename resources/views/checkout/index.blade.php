@extends('layouts.app')
@section('title', 'Finalizar compra | PROCAFES')

@push('styles')
<style>
  .checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    padding: 2rem 1rem;
  }
  .checkout-section, .summary-section {
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    padding: 2rem;
    background: #fff;
  }
  .summary-section {
    background: #fafafa;
    border: 1px solid #eee;
  }
  .form-control, .form-select { border-radius: 6px; border: 1px solid #ccc; }
  .btn-confirm {
    width: 100%;
    background: #473C2B;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    padding: 0.9rem;
    transition: background .3s;
  }
  .btn-confirm:hover { background: #2d2418; }
  .summary-header {
    font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;
    border-bottom: 1px solid #ddd; padding-bottom: .5rem;
  }
  .summary-item { display: flex; justify-content: space-between; margin-bottom: .4rem; }
  .summary-total { border-top: 1px solid #ddd; margin-top: .8rem; padding-top: .8rem; font-size: 1.1rem; font-weight: 600; }
</style>
@endpush

@section('content')
@php
  use Illuminate\Support\Facades\Storage;

  // Carrito en sesión: price = PRECIO FINAL (con IGV)
  $cart  = session('cart', []);
  $items = $cart['items'] ?? [];

  $subtotalBase = 0.0; // base imponible (sin IGV)
  $igvTotal     = 0.0; // IGV total
  $totalFinal   = 0.0; // total (con IGV)

  foreach ($items as $it) {
      $qty        = (float)($it['qty'] ?? 1);
      $unitGross  = (float)($it['price'] ?? 0);          // precio final con IGV
      $lineGross  = round($unitGross * $qty, 2);
      $unitBase   = round($unitGross / 1.18, 2);         // base desde final
      $lineBase   = round($unitBase * $qty, 2);
      $lineIgv    = round($lineGross - $lineBase, 2);    // IGV = final - base

      $subtotalBase += $lineBase;
      $igvTotal     += $lineIgv;
      $totalFinal   += $lineGross;
  }

  $money = fn($n) => number_format((float)$n, 2);
@endphp

<div class="checkout-container">
  {{-- Columna izquierda: datos del cliente --}}
  <div class="checkout-section">
    <h4 class="mb-3">Finalizar compra</h4>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nombre y apellidos</label>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
               class="form-control" required>
      </div>

      <div class="row mb-3">
        <div class="col">
          <label class="form-label">Teléfono</label>
          <input type="text" name="phone" value="{{ old('phone') }}"
                 class="form-control" placeholder="987654321">
        </div>
        <div class="col">
          <label class="form-label">DNI</label>
          <input type="text" name="dni" value="{{ old('dni') }}"
                 class="form-control" inputmode="numeric" maxlength="8" pattern="\d{8}"
                 placeholder="11111111">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Dirección de entrega</label>
        <input type="text" name="address" value="{{ old('address') }}"
               class="form-control" placeholder="Ej: Calle Los Cafetales 123 - Pichanaki" required>
      </div>

      {{-- Métodos de pago (solo 2) --}}
      <div class="mb-4">
        <label class="form-label d-block mb-2">Método de pago</label>

        <div class="form-check mb-2">
          <input class="form-check-input" type="radio" name="method" id="pay_mercadopago"
                 value="mercadopago" {{ old('method','mercadopago')==='mercadopago' ? 'checked' : '' }}>
          <label class="form-check-label" for="pay_mercadopago">
            Tarjeta / Yape / Plin (Mercado Pago)
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="radio" name="method" id="pay_cash"
                 value="efectivo" {{ old('method')==='efectivo' ? 'checked' : '' }}>
          <label class="form-check-label" for="pay_cash">Efectivo</label>
        </div>

        @error('method')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn-confirm">Confirmar compra</button>
    </form>
  </div>

  {{-- Columna derecha: Resumen --}}
  <div class="summary-section">
    <div class="summary-header">Resumen del pedido</div>

    <ul class="list-group mb-3">
      @forelse($items as $item)
        @php
          $qty       = (float)($item['qty'] ?? 1);
          $unitGross = (float)($item['price'] ?? 0);         // final con IGV
          $lineGross = round($unitGross * $qty, 2);

          $img    = $item['image'] ?? null;
          $imgUrl = $img ? (str_starts_with($img, 'http') ? $img : Storage::url($img))
                         : asset('images/placeholder-product.png');
        @endphp

        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-3">
            <img src="{{ $imgUrl }}" alt="{{ $item['name'] ?? 'Producto' }}"
                 style="width:56px;height:56px;object-fit:cover;border-radius:8px;">
            <div>
              <div class="fw-semibold">{{ $item['name'] ?? 'Producto' }}</div>
              <small class="text-muted">x{{ $qty }}</small>
            </div>
          </div>
          <span class="fw-semibold">S/ {{ $money($lineGross) }}</span>
        </li>
      @empty
        <li class="list-group-item text-center text-muted">Tu carrito está vacío.</li>
      @endforelse
    </ul>

    {{-- Totales (derivados del PRECIO FINAL) --}}
    <div class="summary-item">
      <span>Subtotal</span>
      <strong>S/ {{ $money($subtotalBase) }}</strong>
    </div>
    <div class="summary-item">
      <span>IGV (18%)</span>
      <strong>S/ {{ $money($igvTotal) }}</strong>
    </div>
    <div class="summary-total">
      <span>Total</span>
      <span>S/ {{ $money($totalFinal) }}</span>
    </div>
  </div>
</div>
@endsection
