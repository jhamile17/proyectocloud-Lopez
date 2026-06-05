@extends('layouts.app')
@section('title', $title ?? 'Estado de pago')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="mb-3">{{ $title }}</h4>
      <p class="text-muted">Estado: <strong>{{ $status }}</strong></p>

      @if(!empty($q))
        <div class="mt-3">
          <h6>Parámetros recibidos</h6>
          <pre class="small bg-light p-3 rounded border">{{ print_r($q, true) }}</pre>
        </div>
      @endif

      <a href="{{ route('home') }}" class="btn btn-primary mt-3">Volver al inicio</a>
    </div>
  </div>
</div>
@endsection
