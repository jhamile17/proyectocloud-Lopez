<header class="border-bottom" style="background:#F2DD6C;">
  <nav class="container d-flex align-items-center justify-content-between py-2" style="color:#3E350E;">
    {{-- Izquierda: Logo + marca --}}
    <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
      <img src="{{ asset('images/logo.png') }}" alt="PROCAFES" style="height:36px" onerror="this.style.display='none'">
      <span class="fw-bold ms-2" style="color:#3E350E;">PROCAFES</span>
    </a>

    {{-- Centro: menú + buscador (ajusta rutas si tienes páginas) --}}
    <div class="d-none d-md-flex align-items-center flex-grow-1 mx-3" style="max-width:780px;">
      <ul class="nav me-3">
        <li class="nav-item">
          <a href="{{ url('/nosotros') }}" class="nav-link px-2" style="color:#3E350E;">Nosotros chamos</a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/ubicacion') }}" class="nav-link px-2" style="color:#3E350E;">Ubícanos chamos</a>
        </li>
      </ul>

      {{-- Buscador (manda a home con ?q= ) --}}
      <form action="{{ url('/') }}" method="GET" class="d-flex flex-grow-1">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               class="form-control"
               placeholder="Buscar productos..."
               style="background:#FFFBEA;border:1px solid #E0CF61;color:#3E350E;">
        <button class="btn ms-2" type="submit"
                style="background:#E0CF61;color:#3E350E;border:1px solid #D4BF4E;">
          <i class="bi bi-search"></i>
        </button>
      </form>
    </div>

    {{-- Derecha: íconos + auth --}}
    <div class="d-flex align-items-center">
      {{-- Favoritos (placeholder) --}}
      <a href="{{ url('/wishlist') }}" class="btn btn-sm me-2"
         style="background:#E0CF61;border:none;color:#3E350E;">
        <i class="bi bi-heart"></i>
      </a>

      {{-- Carrito con badge (usa tu contador real si lo tienes) --}}
      @php $cartCount = session('cart_count', 0); @endphp
      <a href="{{ url('/cart') }}" class="btn btn-sm position-relative me-3"
         style="background:#E0CF61;border:none;color:#3E350E;">
        <i class="bi bi-cart"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          {{ $cartCount }}
        </span>
      </a>

      @guest
        <a href="{{ route('login') }}" class="btn btn-sm me-2" style="background:#3E350E;color:#FFFFFF;border:none;">
          Iniciar sesión
        </a>
        <a href="{{ route('register') }}" class="btn btn-sm"
           style="background:#DAAD29;color:#3E350E;border:none;">
          Registrarxxse
        </a>
      @endguest

      @auth
        <div class="dropdown">
          <button class="btn btn-sm dropdown-toggle me-2"
                  data-bs-toggle="dropdown"
                  style="background:#E0CF61;color:#3E350E;border:none;">
            {{ Str::limit(auth()->user()->name, 16) }}
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">@csrf
                <button class="dropdown-item">Salir</button>
              </form>
            </li>
          </ul>
        </div>
      @endauth
    </div>
  </nav>
</header>
