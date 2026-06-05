@extends('layouts.admin')
@section('title','Boletas y Facturas | PROCAFES')

@section('admin-content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Boletas y Facturas</h1>
</div>

{{-- ALERTA --}}
@if(session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form id="consulta-form" class="row g-3" method="POST" action="{{ route('admin.billing.lookup') }}">
      @csrf

      <div class="col-12 col-md-3">
        <label class="form-label d-block">Tipo de documento</label>
        <div class="hstack gap-3">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="doc_type" id="tipo_dni" value="dni" checked>
            <label class="form-check-label" for="tipo_dni">DNI</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="doc_type" id="tipo_ruc" value="ruc">
            <label class="form-check-label" for="tipo_ruc">RUC</label>
          </div>
        </div>
        <div class="form-text" id="help-doc">DNI: 8 dígitos • RUC: 11 dígitos</div>
      </div>

      <div class="col-12 col-md-6">
        <label class="form-label">Número</label>
        <input
          id="doc_number"
          name="doc_number"
          class="form-control"
          type="text"
          inputmode="numeric"
          autocomplete="off"
          placeholder="Ingrese DNI o RUC"
          required
        >
      </div>

      <div class="col-12 col-md-3 align-self-end">
        <button type="submit" class="btn btn-dark w-100">
          <i class="bi bi-search me-1"></i> Consultar
        </button>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <h5 class="mb-3">Resultado de consulta</h5>

    <div class="row g-3">
      <div class="col-12 col-md-2">
        <label class="form-label">Tipo</label>
        <input id="result_type" class="form-control" type="text" readonly>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Documento</label>
        <input id="result_document" class="form-control" type="text" readonly>
      </div>

      <div class="col-12 col-md-7">
        <label class="form-label">Nombre</label>
        <input id="result_name" class="form-control" type="text" readonly>
      </div>

      <div class="col-12">
        <label class="form-label">Dirección</label>
        <input id="result_address" class="form-control" type="text" readonly>
      </div>
    </div>
  </div>
</div>

{{-- Bloque para generar desde orden pagada (tu bloque actual, lo mantengo) --}}
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="mb-3">Generar desde orden pagada</h5>
    <form method="POST" action="{{ route('admin.billing.pdf') }}" target="_blank" class="row g-3">
      @csrf

      <div class="col-12 col-md-6">
        <label class="form-label">Selecciona la orden</label>
            <select name="order_id" class="form-select" required>
              <option value="">— Elegir —</option>
              @foreach($orders as $o)
                <option value="{{ $o->id }}">
                  #{{ $o->id }} — {{ $o->customer_name }} — S/ {{ number_format($o->total, 2) }}
                </option>
              @endforeach
            </select>

        <div class="form-text">Solo aparecen órdenes pagadas. Se usará la información de la orden (cliente, ítems, totales) para generar el comprobante.</div>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Tipo de comprobante</label>
        <select name="doc_type" class="form-select">
          <option value="BOLETA">BOLETA</option>
          <option value="FACTURA">FACTURA</option>
        </select>
      </div>

      <div class="col-12 col-md-3 align-self-end">
        <button class="btn btn-dark w-100">
          <i class="bi bi-printer me-1"></i> Generar e imprimir
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const $number = document.getElementById('doc_number');
  const radios  = document.querySelectorAll('input[name="doc_type"]');

  function applyConstraints() {
    const type = document.querySelector('input[name="doc_type"]:checked').value; // 'dni' | 'ruc'
    if (type === 'dni') {
      $number.setAttribute('maxlength', '8');
      $number.setAttribute('minlength', '8');
      $number.setAttribute('pattern', '\\d{8}');
      $number.placeholder = 'DNI (8 dígitos)';
    } else { // ruc
      $number.setAttribute('maxlength', '11');
      $number.setAttribute('minlength', '11');
      $number.setAttribute('pattern', '\\d{11}');
      $number.placeholder = 'RUC (11 dígitos)';
    }
    // recorta si sobra
    const max = parseInt($number.getAttribute('maxlength'), 10);
    if ($number.value.length > max) $number.value = $number.value.slice(0, max);
  }

  // Solo dígitos y límite por tipo.
  $number.addEventListener('input', function () {
    this.value = this.value.replace(/\D+/g, '');
    const max = parseInt(this.getAttribute('maxlength') || '11', 10);
    if (this.value.length > max) this.value = this.value.slice(0, max);
  });

  radios.forEach(r => r.addEventListener('change', applyConstraints));
  applyConstraints();

  // Envío AJAX para pintar resultados
  const form = document.getElementById('consulta-form');
  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const fd = new FormData(form);
    try {
      const res = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: fd
      });

      if (!res.ok) throw new Error('Error de consulta');

      const data = await res.json();
      // Esperamos: { ok:true, type:'DNI'|'RUC', document:'', name:'', address:'' }
      if (data.ok) {
        document.getElementById('result_type').value      = data.type || '';
        document.getElementById('result_document').value  = data.document || '';
        document.getElementById('result_name').value      = data.name || '';
        document.getElementById('result_address').value   = data.address || '';
      } else {
        document.getElementById('result_type').value      = '';
        document.getElementById('result_document').value  = '';
        document.getElementById('result_name').value      = '';
        document.getElementById('result_address').value   = '';
        alert(data.message || 'No se encontró información.');
      }
    } catch (err) {
      alert('No se pudo completar la consulta. Intente de nuevo.');
    }
  });
})();
</script>
@endpush
