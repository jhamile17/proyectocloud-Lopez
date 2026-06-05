@extends('layouts.app')
@section('title', 'Mi carrito')

@section('content')
<div class="container py-4">
  <h3 class="mb-4">🛒 Mi carrito</h3>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  @if($items->isEmpty())
    <div class="alert alert-secondary">Tu carrito está vacío.</div>
  @else
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th style="width:160px;">Cantidad</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $it)
            <tr>
              <td>{{ $it->product->name ?? '—' }}</td>
              <td>S/ {{ number_format($it->price, 2) }}</td>
              <td>
                <form action="{{ route('cart.update', $it->product_id) }}" method="POST" class="d-flex gap-2">
                  @csrf @method('PATCH')
                  <input type="number" name="qty" min="1" value="{{ $it->quantity }}" class="form-control form-control-sm" style="width:80px;">
                  <button class="btn btn-sm btn-outline-primary">Actualizar</button>
                </form>
              </td>
              <td>S/ {{ number_format($it->sub_total, 2) }}</td>
              <td class="text-end">
                <form action="{{ route('cart.remove', $it->product_id) }}" method="POST">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total</th>
            <th>S/ {{ number_format($total, 2) }}</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  @endif
</div>
@endsection
