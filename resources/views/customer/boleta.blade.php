<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Boleta PROCAFES</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#111; }
    .row { display:flex; justify-content:space-between; gap: 10px; }
    .box { border:1px solid #ddd; padding:10px; border-radius:6px; }
    .title { font-size:16px; font-weight:bold; margin:0; }
    .muted { color:#666; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    th, td { border:1px solid #ddd; padding:6px; }
    th { background:#f3f3f3; text-align:left; }
    .right { text-align:right; }
    .mt-10 { margin-top:10px; }
  </style>
</head>
<body>

  <div class="row">
    <div>
      <p class="title">PROCAFES</p>
      <p class="muted" style="margin:0;">Boleta de venta</p>
    </div>

    <div class="box" style="min-width: 220px;">
      <div><strong>Pedido #:</strong> {{ $order->id }}</div>
      <div><strong>Fecha:</strong> {{ $order->created_at ?? '-' }}</div>
      <div><strong>Estado:</strong> {{ $order->status ?? '-' }}</div>
    </div>
  </div>

  <div class="row mt-10">
    <div class="box" style="width: 48%;">
      <strong>Cliente</strong><br>
      {{ $user->name }}<br>
      {{ $user->email }}<br>
      @if(!empty($user->phone)) Tel: {{ $user->phone }}<br> @endif
    </div>

    <div class="box" style="width: 48%;">
      <strong>Datos del negocio</strong><br>
      PROCAFES<br>
      Dirección: (tu dirección)<br>
      RUC: (tu RUC si aplica)<br>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th class="right">Precio</th>
        <th class="right">Cant.</th>
        <th class="right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $it)
        @php
          $qty = $it->quantity ?? $it->qty ?? 1;
          $price = $it->price ?? $it->unit_price ?? $it->product_price ?? 0;
          $subtotal = $price * $qty;
        @endphp
        <tr>
          <td>{{ $it->product_name ?? 'Producto' }}</td>
          <td class="right">S/ {{ number_format((float)$price, 2) }}</td>
          <td class="right">{{ $qty }}</td>
          <td class="right">S/ {{ number_format((float)$subtotal, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row mt-10">
    <div></div>
    <div class="box" style="width: 40%;">
      <div class="row">
        <div><strong>Total</strong></div>
        <div><strong>S/ {{ number_format((float)$total, 2) }}</strong></div>
      </div>
      <div class="muted" style="margin-top:6px;">Gracias por tu compra.</div>
    </div>
  </div>

</body>
</html>
