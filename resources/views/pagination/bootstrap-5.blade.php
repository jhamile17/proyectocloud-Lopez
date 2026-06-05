<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title','Admin | PROCAFES')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Bootstrap (si ya está via Vite, puedes quitar este CDN) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --brand-100:#F2DD6C; /* header/btns */
      --brand-200:#DAAD29; /* hover/acentos */
      --brand-700:#794515; /* iconos/links */
      --brand-900:#3E350E; /* oscuro */
      --surface:#EAE9E7;   /* fondo */
      --text:#2b2b2b;
    }
    body{ background: var(--surface); }

    /* Topbar */
    .admin-topbar{ background: var(--brand-100); border-bottom:1px solid rgba(0,0,0,.06); }
    .btn-brand{ background:var(--brand-100); color:#2b2b2b; border:0; }
    .btn-brand:hover{ background:var(--brand-200); color:#1b1b1b; }
    .btn-outline-brand{ border:1px solid var(--brand-200); color:var(--brand-700); }
    .btn-outline-brand:hover{ background:var(--brand-100); }

    /* Sidebar estilo limpio */
    .admin-sidebar{ background:#1e232a; color:#c9d1d9; min-height:100vh; }
    .admin-sidebar a{ color:#c9d1d9; text-decoration:none; border-radius:.5rem; padding:.6rem .75rem; display:flex; align-items:center; gap:.5rem; }
    .admin-sidebar a:hover{ background:rgba(255,255,255,.06); }
    .admin-sidebar a.active{ background:rgba(255,255,255,.10); }

    /* Cards suaves */
    .card-soft{ background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.04); }
    .card-soft .card-header{ background:transparent; border-bottom:1px solid rgba(0,0,0,.06); }

    /* Breadcrumb */
    .breadcrumb-lite{ font-size:.93rem; color:#6b7280; }
    .breadcrumb-lite a{ color:var(--brand-700); text-decoration:none; }
    .breadcrumb-lite .sep{ margin:0 .35rem; color:#9aa1aa; }
  </style>

  @stack('styles')
</head>
<body>
  {{-- Topbar --}}
  <nav class="admin-topbar py-2">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-3">
        <img src="{{ asset('images/logo.png') }}" alt="PROCAFES" style="height:36px;">
        <strong>PROCAFES ADMIN</strong>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/admin/products') }}" class="btn btn-outline-brand">Ver productos</a>
        <a href="{{ url('/admin') }}" class="btn btn-brand">Dashboard</a>
        <form action="{{ route('logout') }}" method="POST" class="ms-2">
          @csrf
          <button class="btn btn-sm btn-outline-dark">Salir</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      {{-- Sidebar --}}
      <aside class="col-12 col-md-3 col-lg-2 p-3 admin-sidebar">
        <div class="text-center mb-4">
          <img src="{{ asset('images/avatar_admin.png') }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;">
          <div class="mt-2 fw-semibold">Administrador</div>
        </div>
        <nav class="d-grid gap-1">
          <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
          </a>
          <a href="{{ url('/admin/products') }}" class="{{ request()->is('admin/products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> <span>Productos</span>
          </a>
          <a href="{{ url('/admin/brands') }}" class="{{ request()->is('admin/brands*') ? 'active' : '' }}">
            <i class="bi bi-award"></i> <span>Marcas</span>
          </a>
          <a href="{{ url('/admin/categories') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> <span>Categorías</span>
          </a>
          <a href="{{ url('/admin/users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> <span>Usuarios</span>
          </a>
          <a href="{{ route('admin.billing.index') }}"
   class="list-group-item list-group-item-action {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">
  <i class="bi bi-receipt-cutoff me-2"></i> Boletas y Facturas
</a>

          <hr class="border-secondary">
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.nextElementSibling.submit();">
            <i class="bi bi-box-arrow-right"></i> <span>Cerrar sesión</span>
          </a>
          <form action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </nav>
      </aside>

      {{-- Contenido --}}
      <main class="col-12 col-md-9 col-lg-10 p-4">
        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
