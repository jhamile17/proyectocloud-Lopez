@extends('layouts.admin')
@section('title','Órdenes | PROCAFES')

@push('styles')
<style>
  /* Contenedor */
  .page-wrap{max-width:1200px;margin-inline:auto}
  /* Toolbar filtros */
  .toolbar .form-control,.toolbar .form-select{height:42px}
  /* Tarjeta tabla */
  .card-table{border:0;box-shadow:0 6px 20px rgba(15,23,42,.06)}
  /* Tabla */
  .table thead th{font-weight:600;color:#6b7280;background:#f8fafc;border-bottom:1px solid #e5e7eb}
  .table tbody td{vertical-align:middle;border-color:#f1f5f9}
  .table-hover tbody tr:hover{background:#f9fafb}
  .col-money{width:140px}
  .col-id{width:72px}
  .col-date{width:170px}
  .col-actions{width:88px}
  .col-status{width:220px}
  /* Estado */
  .badge-status{font-weight:600;letter-spacing:.2px}
  .badge-paid{background:#dcfce7;color:#065f46}
  .badge-proc{background:#fff7ed;color:#9a3412}
  .badge-cancel{background:#fee2e2;color:#7f1d1d}
  /* Cabecera pegajosa */
  .sticky-head thead th{position:sticky;top:0;z-index:1}
  /* Empty state */
  .empty{padding:48px 16px;color:#64748b}
</style>
@endpush

@section('admin-content')
@php
  // Mapeos de estado → etiqueta/clase
  $statusLabel = [
    'paid'       => 'Pagado',
    'shipped'    => 'Enviado',
    'completed'  => 'Completado',
    'success'    => 'Completado',
    'processing' => 'Procesando',
    'pending'    => 'Pendiente',
    'cancelled'  => 'Cancelado',
    'canceled'   => 'Cancelado',
    'failed'     => 'Fallido',
  ];
  $statusClass = [
    'paid'       => 'badge-paid',
    'shipped'    => 'badge-paid',
    'completed'  => 'badge-paid',
    'success'    => 'badge-paid',
    'processing' => 'badge-proc',
    'pending'    => 'badge-proc',
    'cancelled'  => 'badge-cancel',
    'canceled'   => 'badge-cancel',
    'failed'     => 'badge-cancel',
  ];

  // Normalizador para el total (total_price | total)
  $fmt = fn($n) => number_format((float)$n, 2);
@endphp

<div class="container-fluid">
  <div class="page-wrap">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h3 class="mb-0">Órdenes</h3>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-speedometer2 me-1"></i> Volver al panel
      </a>
    </div>

    @if(session('status'))  <div class="alert alert-success py-2 px-3">{{ session('status') }}</div> @endif
    @if(session('warning')) <div class="alert alert-warning py-2 px-3">{{ session('warning') }}</div> @endif

    {{-- Toolbar de filtros --}}
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <form class="row g-2 toolbar" method="GET">
          <div class="col-md-5">
            <input type="text" name="q" class="form-control"
                   placeholder="Buscar cliente, email o #ID…"
                   value="{{ $q }}">
          </div>
          <div class="col-md-4">
            <select name="status" class="form-select">
              <option value="">Todos los estados</option>
              @foreach($statuses as $st)
                @php $k = strtolower($st); @endphp
                <option value="{{ $st }}" @selected($status===$st)>
                  {{ $statusLabel[$k] ?? strtoupper($st) }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 d-grid d-sm-flex gap-2">
            <button class="btn btn-dark flex-fill">
              <i class="bi bi-search me-1"></i> Filtrar
            </button>
            @if(request()->hasAny(['q','status']) && (request('q') || request('status')))
              <a class="btn btn-outline-secondary flex-fill" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-x-circle me-1"></i> Limpiar
              </a>
            @endif
          </div>
        </form>
      </div>
    </div>

    {{-- Tabla --}}
    <div class="card card-table">
      <div class="table-responsive">
        <table class="table table-hover align-middle sticky-head mb-0">
          <thead>
            <tr>
              <th class="col-id">#</th>
              <th>Cliente</th>
              <th class="col-status">Estado</th>
              <th class="text-end col-money">Total</th>
              <th class="col-date">Fecha</th>
              <th class="col-actions"></th>
            </tr>
          </thead>
          <tbody>
          @forelse($orders as $o)
            @php
              $key  = strtolower($o->status ?? '');
              $tag  = $statusLabel[$key] ?? ucfirst($key ?: '—');
              $cls  = $statusClass[$key] ?? 'bg-secondary text-white';
              $tot  = $o->total_price ?? $o->total ?? 0;
            @endphp
            <tr>
              <td class="text-muted">#{{ $o->id }}</td>
              <td>
                <div class="fw-semibold">{{ $o->customer_name ?? '—' }}</div>
                <small class="text-muted">{{ $o->customer_email ?? '—' }}</small>
              </td>

              {{-- Estado: badge + selector que auto-guarda --}}
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge badge-status {{ $cls }}">{{ $tag }}</span>
                  <form action="{{ route('admin.orders.status', $o->id) }}" method="POST" class="d-inline status-form">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm status-select">
                      <option value="pending"   @selected($o->status === 'pending')>Pendiente</option>
                      <option value="paid"      @selected($o->status === 'paid')>Pagado</option>
                      <option value="cancelled" @selected($o->status === 'cancelled' || $o->status === 'canceled')>Cancelado</option>
                    </select>
                    <noscript><button class="btn btn-sm btn-outline-secondary ms-2">Guardar</button></noscript>
                  </form>
                </div>
              </td>

              <td class="text-end">S/ {{ $fmt($tot) }}</td>
              <td>{{ \Illuminate\Support\Carbon::parse($o->created_at)->format('d/m/Y H:i') }}</td>
              <td class="text-end">
                <a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center empty">
                <i class="bi bi-inbox me-2"></i> No hay órdenes que coincidan con el filtro.
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if($orders->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
          <div class="small text-muted">
            Mostrando {{ $orders->firstItem() }}–{{ $orders->lastItem() }} de {{ $orders->total() }} resultados
          </div>
          {{ $orders->onEachSide(1)->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Auto-submit del form al cambiar el estado
  document.querySelectorAll('.status-select').forEach(function(sel){
    sel.addEventListener('change', function(){
      this.closest('form').submit();
    });
  });
</script>
@endpush
