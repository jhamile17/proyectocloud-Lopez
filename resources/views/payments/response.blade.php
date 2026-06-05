@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Resultado del pago</h1>
  <pre class="p-3 bg-light border rounded">{{ print_r($data, true) }}</pre>
  <a href="{{ route('payu.form') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
