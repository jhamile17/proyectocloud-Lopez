@php
  $pcf = [
    'primary' => '#f2dd6c',
    'dark'    => '#3e350e',
    'bg'      => '#faf8ef',
  ];
@endphp
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Comprobante | Orden #{{ $order->id }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{ background: {{ $pcf['bg'] }}; }
    .stat{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:.5rem;}
    .brand{font-weight:700;letter-spacing:.5px;color:{{ $pcf['dark'] }}}
    .badge-soft{background:{{ $pcf['primary'] }};color:{{ $pcf['dark'] }}}
    @media print {
      .no-print { display:none !important; }
      body { background:#fff; }
    }
  </style>
</head>
<body class="py-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-start mb-3">
      <div>
        <div class="brand h4 mb-0">PROCAFES</div>
        <div class="text-muted">Comprobante generado desde la orden</div>
      </div>
      <div class="no-print">
        <button onclick="window.print()" class="btn btn-dark">
          <i class="bi bi-printer me-1"></i> Imprimir
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Orden #{{ $order->id }}</h5>
      <span class="badge badge-soft">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <div class="stat p-3">
          <div class="fw-semibold mb-2">Resumen</div>
          <div class="d-flex justify-content-between"><span>Total</span><strong>S/
            {{ number_format($order->total_price ?? $order->total ?? $order->items->sum('subtotal'), 2) }}</strong></div>
          <div class="d-flex justify-content-between"><span>Fecha</span><span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="stat p-3">
          <div class="fw-semibold mb-2">Cliente</div>
          <div class="d-flex justify-content-between"><span>Nombre</span><span>{{ $order->user?->name ?? '—' }}</span></div>
          <div class="d-flex justify-content-between"><span>Email</span><span>{{ $order->user?->email ?? '—' }}</span></div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th class="text-muted">#</th>
                <th>Producto</th>
                <th class="text-center">Cant.</th>
                <th class="text-end">P. Unit.</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $i => $it)
                <tr>
                  <td class="text-muted">{{ $i+1 }}</td>
                  <td>{{ $it->product?->name ?? '—' }}</td>
                  <td class="text-center">{{ number_format($it->quantity ?? 0, 2) }}</td>
                  <td class="text-end">S/ {{ number_format($it->unit_price ?? 0, 2) }}</td>
                  <td class="text-end">S/ {{ number_format($it->subtotal ?? 0, 2) }}</td>
                </tr>
              @endforeach
              <tr>
                <td colspan="4" class="text-end fw-semibold">Total</td>
                <td class="text-end fw-semibold">
                  S/ {{ number_format($order->total_price ?? $order->total ?? $order->items->sum('subtotal'), 2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="text-muted small">
          * Este documento es una visualización para impresión en navegador.  
          Si luego deseas **PDF oficial**, podemos integrar DomPDF o un servicio de facturación electrónica.
        </div>
      </div>
    </div>
  </div>
</body>
</html>
