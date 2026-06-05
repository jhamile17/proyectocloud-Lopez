@extends('layouts.admin')
@section('title','Panel | PROCAFES')

@push('styles')
<style>
  :root{
    --pcf-primary:#f2dd6c;
    --pcf-dark:#3e350e;
    --pcf-bg:#faf8ef;
  }
  body{ background:var(--pcf-bg); }
  .chip{
    display:flex; align-items:center; gap:.5rem;
    background:#fff; border:1px solid rgba(0,0,0,.08);
    padding:.65rem .8rem; border-radius:.75rem; white-space:nowrap;
  }
  .chip i{ color:var(--pcf-dark); }
  .stat-card{ border:1px solid rgba(0,0,0,.06); }
  .stat-ico{
    width:44px;height:44px;border-radius:.75rem;
    display:grid;place-items:center; background:var(--pcf-primary); color:var(--pcf-dark);
  }
  .btn-procafes{ background:var(--pcf-dark); color:#fff; border:0; }
  .btn-procafes:hover{ filter:brightness(1.08); color:#fff; }
  .link-muted{ color:#6c757d; text-decoration:none;}
  .link-muted:hover{ color:#495057; }
  .scroll-x{ overflow:auto; }
  .shadow-soft{ box-shadow:0 .5rem 1rem rgba(0,0,0,.06)!important; }
  .badge-soft{ background:var(--pcf-primary); color:var(--pcf-dark) }
  .mini-help{ font-size:.8rem; color:#6c757d }
</style>
@endpush

@section('admin-content')

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 mb-0">Panel</h1>

    <div class="d-flex gap-2">
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('home') }}" target="_blank">
        <i class="bi bi-shop-window me-1"></i> Ver tienda
      </a>

      <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-procafes">
        <i class="bi bi-plus-lg me-1"></i> Nuevo producto
      </a>

      {{-- Reportes --}}
      <div class="dropdown">
          <button class="btn btn-sm btn-outline-dark dropdown-toggle"
                  data-bs-toggle="dropdown">
              <i class="bi bi-download me-1"></i>
              Reportes
          </button>

          <ul class="dropdown-menu dropdown-menu-end">

              {{-- Ventas por fechas --}}
              <li>
                  <a class="dropdown-item"
                     href="{{ route('admin.reports.sales-by-date', [
                          'from' => now()->subMonth()->toDateString(),
                          'to' => now()->toDateString()
                     ]) }}">
                      <i class="bi bi-calendar-range me-2"></i>
                      Ventas últimos 12 meses
                  </a>
              </li>

              {{-- Más vendidos --}}
              <li>
                  <a class="dropdown-item"
                     href="{{ route('admin.reports.best') }}">
                      <i class="bi bi-trophy me-2"></i>
                      Productos más vendidos
                  </a>
              </li>

              {{-- Menos vendidos --}}
              <li>
                  <a class="dropdown-item"
                     href="{{ route('admin.reports.least') }}">
                      <i class="bi bi-graph-down me-2"></i>
                      Productos menos vendidos
                  </a>
              </li>

              {{-- Inventario crítico --}}
              <li>
                  <a class="dropdown-item"
                     href="{{ route('admin.reports.inventory') }}">
                      <i class="bi bi-box-seam me-2"></i>
                      Inventario crítico
                  </a>
              </li>

              {{-- Ventas por categoría --}}
              <li>
                  <a class="dropdown-item"
                     href="{{ route('admin.reports.category') }}">
                      <i class="bi bi-cup-hot me-2"></i>
                      Ventas por categoría
                  </a>
              </li>

          </ul>
      </div>
    </div>
  </div>

  {{-- Tarjetas de métricas --}}
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card stat-card shadow-soft">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-ico"><i class="bi bi-coin fs-5"></i></div>
          <div class="flex-grow-1">
            <div class="small text-muted">Ingresos totales</div>
            <div class="fs-4 fw-bold">S/ {{ number_format($stats['revenue'] ?? 0, 2) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card stat-card shadow-soft">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-ico"><i class="bi bi-bag-check fs-5"></i></div>
          <div class="flex-grow-1">
            <div class="small text-muted">Órdenes totales</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['orders'] ?? 0) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card stat-card shadow-soft">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-ico"><i class="bi bi-box-seam fs-5"></i></div>
          <div class="flex-grow-1">
            <div class="small text-muted">Productos totales</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['products'] ?? 0) }}</div>
          </div>
          <a class="link-muted small" href="{{ route('admin.products.index') }}">Crear nuevo</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card stat-card shadow-soft">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-ico"><i class="bi bi-people fs-5"></i></div>
          <div class="flex-grow-1">
            <div class="small text-muted">Clientes totales</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['customers'] ?? 0) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Categorías --}}
  <div class="card shadow-soft mb-3">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0">Categorías</h6>
        <a href="{{ route('admin.categories.index') }}" class="link-muted small">Gestionar</a>
      </div>
      <div class="d-flex gap-2 scroll-x">
        @forelse($chips ?? [] as $c)
          <div class="chip"><i class="bi {{ $c['i'] }}"></i> {{ $c['t'] }}</div>
        @empty
          <span class="text-muted small">No hay categorías</span>
        @endforelse
      </div>
    </div>
  </div>

  @php
    $hasRevenue = collect($revenue ?? [])->sum() > 0; // fallback si no carga AJAX
  @endphp

  {{-- Reporte de ingresos + Más vendidos --}}
  <div class="row g-3">
    <div class="col-12 col-xl-7">
      <div class="card shadow-soft h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0">Reporte de ingresos</h6>

            {{-- Controles de rango y agrupación --}}
            <div class="d-flex flex-wrap align-items-center gap-2">
              <div class="d-flex align-items-center gap-2">
                <span class="mini-help">Agrupar por</span>
                <select id="group" class="form-select form-select-sm">
                  <option value="year">Año</option>
                  <option value="month" selected>Mes</option>
                  <option value="week">Semana</option>
                  <option value="day">Día</option>
                </select>
              </div>

              <div class="d-flex align-items-center gap-1">
                <input id="from" type="date" class="form-control form-control-sm">
                <span class="mini-help">—</span>
                <input id="to" type="date" class="form-control form-control-sm">
              </div>

              <button id="apply" class="btn btn-sm btn-outline-secondary">Aplicar</button>
              <button id="reset" class="btn btn-sm btn-outline-dark">Últimos 12 meses</button>
            </div>
          </div>

          <div id="chartWrap" style="height:300px">
            <canvas id="revChart"></canvas>
          </div>

          <div id="chartEmpty" class="text-center text-muted py-5 d-none">
            <i class="bi bi-graph-up" style="font-size:2rem;"></i>
            <p class="mb-0 mt-2">Aún no hay ingresos para graficar.</p>
            <small>Cuando registres ventas pagadas, el gráfico aparecerá aquí.</small>
          </div>

          <div id="chartError" class="alert alert-danger d-none mt-3 mb-0"></div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-5">
      <div class="card shadow-soft h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0">Productos más vendidos</h6>
            <span class="small text-muted">Top 5</span>
          </div>
          <ul class="list-group list-group-flush">
            @forelse($best ?? [] as $b)
              <li class="list-group-item d-flex align-items-center">
                <img class="rounded me-3" src="{{ $b['img'] ?? 'https://via.placeholder.com/56' }}" width="48" height="48" alt="">
                <div class="flex-grow-1">
                  <div class="fw-semibold">{{ $b['name'] }}</div>
                  @if(!empty($b['sku']))<div class="text-muted small">{{ $b['sku'] }}</div>@endif
                </div>
                <div class="text-end small">
                  <div>Unid.: {{ (int)($b['orders'] ?? 0) }}</div>
                  <div class="fw-semibold">Importe: S/ {{ number_format((float)($b['total'] ?? 0), 2) }}</div>
                </div>
              </li>
            @empty
              <li class="list-group-item text-muted small">Aún no hay ventas</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  (function () {
    const PCF_DARK = getComputedStyle(document.documentElement).getPropertyValue('--pcf-dark').trim();
    const PCF_PRIM = getComputedStyle(document.documentElement).getPropertyValue('--pcf-primary').trim();

    // Elementos
    const $group = document.getElementById('group');
    const $from  = document.getElementById('from');
    const $to    = document.getElementById('to');
    const $apply = document.getElementById('apply');
    const $reset = document.getElementById('reset');
    const $wrap  = document.getElementById('chartWrap');
    const $empty = document.getElementById('chartEmpty');
    const $err   = document.getElementById('chartError');

    // Rango por defecto: últimos 12 meses
    function setDefaultRange() {
      const end   = new Date();
      const start = new Date(end);
      start.setMonth(end.getMonth() - 11);
      start.setDate(1);

      $from.value = start.toISOString().slice(0,10);
      $to.value   = end.toISOString().slice(0,10);
      $group.value= 'month';
    }
    setDefaultRange();

    // Chart
    let chart;
    function renderChart(labels, data) {
      const el = document.getElementById('revChart');
      if (chart) chart.destroy();

      if (!labels.length || !data.some(v => Number(v) > 0)) {
        $wrap.classList.add('d-none');
        $empty.classList.remove('d-none');
        return;
      }
      $empty.classList.add('d-none');
      $wrap.classList.remove('d-none');

      chart = new Chart(el, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Ingresos',
            data,
            fill: true,
            borderColor: PCF_DARK,
            backgroundColor: PCF_PRIM + '55',
            tension: .35,
            pointRadius: 2,
            pointBackgroundColor: PCF_DARK,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              grid: { color:'rgba(0,0,0,.05)' },
              ticks: { callback: (v) => `S/ ${Number(v).toFixed(2)}` }
            },
            x: { grid: { display:false } }
          },
          plugins: {
            legend: { display:false },
            tooltip: { callbacks: { label: (ctx) => `S/ ${Number(ctx.parsed.y).toFixed(2)}` } }
          }
        }
      });
    }

    // Endpoint JSON
    const JSON_URL = @json( Route::has('admin.reports.revenue.json')
                      ? route('admin.reports.revenue.json')
                      : url('/admin/reports/revenue.json') );

    async function loadData() {
      try {
        $err.classList.add('d-none');
        const params = new URLSearchParams({
          group: $group.value || 'month',
          from:  $from.value,
          to:    $to.value,
        });
        const res = await fetch(`${JSON_URL}?${params.toString()}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const js = await res.json();
        if (!js.ok) throw new Error('Respuesta inválida');

        renderChart(js.labels || [], (js.data || []).map(Number));
      } catch (e) {
        $err.textContent = 'No se pudo cargar el reporte. ' + (e?.message || '');
        $err.classList.remove('d-none');
        renderChart([], []);
      }
    }

    // Eventos
    $apply.addEventListener('click', loadData);
    $reset.addEventListener('click', () => { setDefaultRange(); loadData(); });

    // Primer render
    loadData();
  })();
  </script>
@endpush
