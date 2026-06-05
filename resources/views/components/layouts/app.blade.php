<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <title>@yield('title','PROCAFES')</title>

  <style>
    .bg-procafes { background-color:#f2da66; }
    .btn-procafes-dark { background-color:#2c2c2c; color:#fff; }
    .btn-procafes-dark:hover { filter:brightness(1.1); }
    .btn-procafes-accent { background-color:#dcae3e; color:#2c2c2c; }
    .btn-procafes-accent:hover { filter:brightness(0.95); }
    a.link-procafes { color:#2c2c2c; }
    a.link-procafes:hover { color:#2c2c2c; }
  </style>
   <script>
  window.Laravel = {
    csrfToken: '{{ csrf_token() }}',
    routes: {
      index:  '{{ route('cart.index') }}',
      add:    '{{ route('cart.add') }}',
      update: '{{ route('cart.update', ['rowId' => '__ID__']) }}',
      remove: '{{ route('cart.remove', ['rowId' => '__ID__']) }}',
      clear:  '{{ route('cart.clear') }}',
    }
  };
  window.App = {
    isAuth: {{ auth()->check() ? 'true' : 'false' }},
    routes: {
      login:    '{{ route('login') }}',
      @if (Route::has('checkout')) checkout: '{{ route('checkout') }}', @endif
    }
  };
</script>

  @stack('styles')
</head>
<body class="bg-light">

  {{-- Header minimal --}}
  @include('partials.header-auth')

  {{-- Aquí Volt inyecta el contenido de la página --}}
  <main class="container py-5">
    {{ $slot }}
  </main>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></>
  {{-- Opcional: scripts de vistas --}}
  @stack('scripts')
</body>
</html>
