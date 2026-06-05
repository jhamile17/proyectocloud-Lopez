@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Pagar Demo con PayU (Sandbox)</h1>

  <form method="POST" action="{{ route('payu.checkout') }}" class="vstack gap-3">
    @csrf
    <div>
      <label class="form-label">Monto (PEN)</label>
      <input type="number" step="0.01" min="1" name="amount" class="form-control" value="10.00">
    </div>
    <button class="btn btn-primary">Ir a pagar con PayU</button>
  </form>
</div>
@endsection
