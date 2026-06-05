@extends('layouts.app')
@section('title', 'Pagar con Mercado Pago | PROCAFES')

@push('styles')
<style>
  .mp-wrap { max-width: 900px; margin: 2rem auto; }
  .mp-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
  .card { border-radius: 10px; }
  .thumb { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; }
  .btn-mp { background:#00a650; border:0; }
  .btn-mp:hover { background:#029347; }
</style>
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    // Items del carrito (price = PRECIO FINAL con IGV)
    $cart  = session('cart', []);
    $items = is_array($cart['items'] ?? null) ? $cart['items'] : [];

    // Totales desde PRECIO FINAL:
    // Total = suma(qty * price)
    // IGV   = 18% del Total
    // Base  = Total - IGV
    $totalFinal = 0.0;
    foreach ($items as $it) {
        $qty   = (float)($it['qty'] ?? 1);
        $price = (float)($it['price'] ?? 0);
        $totalFinal += $qty * $price;
    }
    $totalFinal = round($totalFinal, 2);
    $igv        = round($totalFinal * 0.18, 2);
    $base       = round($totalFinal - $igv, 2);

    // Order ID opcional por querystring
    $orderId = request('order_id');
    $money   = fn($n)=> number_format((float)$n, 2);
@endphp

<div class="mp-wrap">
  <h3 class="mb-3">Pagar con Mercado Pago</h3>

  <div class="mp-grid">
    {{-- Columna izquierda: Detalle de productos --}}
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <strong>Resumen del carrito</strong>
      </div>
      <div class="card-body">
        @if(empty($items))
          <div class="text-center text-muted">Tu carrito está vacío.</div>
        @else
          <ul class="list-group list-group-flush mb-3">
            @foreach($items as $it)
              @php
                $qty       = (float)($it['qty'] ?? 1);
                $unitGross = (float)($it['price'] ?? 0);        // final con IGV
                $lineGross = round($qty * $unitGross, 2);

                $img    = $it['image'] ?? null;
                $imgUrl = $img
                  ? (Str::startsWith($img, ['http://','https://']) ? $img : Storage::url($img))
                  : asset('images/placeholder-product.png');
              @endphp
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                  <img class="thumb" src="{{ $imgUrl }}" alt="{{ $it['name'] ?? 'Producto' }}">
                  <div>
                    <div class="fw-semibold">{{ $it['name'] ?? 'Producto' }}</div>
                    <small class="text-muted">x{{ $qty }}</small>
                  </div>
                </div>
                <strong>S/ {{ $money($lineGross) }}</strong>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    {{-- Columna derecha: Totales + botón pagar --}}
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <strong>Totales</strong>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-1">
          <span>Subtotal</span>
          <strong>S/ {{ $money($base) }}</strong>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span>IGV (18%)</span>
          <strong>S/ {{ $money($igv) }}</strong>
        </div>
        <hr>
        <div class="d-flex justify-content-between fs-5 mb-3">
          <span>Total</span>
          <strong>S/ {{ $money($totalFinal) }}</strong>
        </div>

        <form method="POST" action="{{ route('mp.preference') }}">
          @csrf
          <input type="hidden" name="order_id" value="{{ $orderId ?? 0 }}">
          {{-- Enviar SIEMPRE el total FINAL (con IGV) --}}
          <input type="hidden" name="total" value="{{ $totalFinal }}">
          <button type="submit" class="btn btn-mp w-100 text-white">
            Pagar ahora con Mercado Pago
          </button>
        </form>

        <small class="text-muted d-block mt-3">
          Serás redirigido a Mercado Pago para completar el pago con tarjeta, Yape o Plin.
        </small>
      </div>
    </div>
  </div>
</div>
@endsection
