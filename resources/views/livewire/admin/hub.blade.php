{{-- NO pongas nada fuera del root. Ni comentarios HTML, ni scripts, nada. --}}

<section class="container-fluid py-3"> {{-- ÚNICO ROOT DEL COMPONENTE --}}

  <div class="row">
    {{-- Sidebar --}}
    <aside class="col-12 col-md-3 col-lg-2 mb-3">
      <div class="list-group shadow-sm">
        <button class="list-group-item list-group-item-action {{ $tab==='dashboard' ? 'active' : '' }}"
                wire:click="setTab('dashboard')">
          <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </button>
        <button class="list-group-item list-group-item-action {{ $tab==='products' ? 'active' : '' }}"
                wire:click="setTab('products')">
          <i class="bi bi-box-seam me-2"></i> Productos
        </button>
        <button class="list-group-item list-group-item-action {{ $tab==='categories' ? 'active' : '' }}"
                wire:click="setTab('categories')">
          <i class="bi bi-grid me-2"></i> Categorías
        </button>
        <button class="list-group-item list-group-item-action {{ $tab==='brands' ? 'active' : '' }}"
                wire:click="setTab('brands')">
          <i class="bi bi-tags me-2"></i> Marcas
        </button>
        <button class="list-group-item list-group-item-action {{ $tab==='customers' ? 'active' : '' }}"
                wire:click="setTab('customers')">
          <i class="bi bi-people me-2"></i> Clientes
        </button>
        <button class="list-group-item list-group-item-action {{ $tab==='orders' ? 'active' : '' }}"
                wire:click="setTab('orders')">
          <i class="bi bi-receipt me-2"></i> Órdenes
        </button>
      </div>
    </aside>

    {{-- Contenido dinámico --}}
    <section class="col-12 col-md-9 col-lg-10">
      @if ($tab === 'dashboard')
        <div class="row g-3">
          <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
              <div class="small text-muted">Total Revenue</div>
              <div class="h4 mb-0">S/ 0</div>
            </div></div>
          </div>
          <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
              <div class="small text-muted">Total Orders</div>
              <div class="h4 mb-0">0</div>
            </div></div>
          </div>
          <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
              <div class="small text-muted">Total Products</div>
              <div class="h4 mb-0">0</div>
            </div></div>
          </div>
          <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
              <div class="small text-muted">Customers</div>
              <div class="h4 mb-0">0</div>
            </div></div>
          </div>
        </div>

      @elseif ($tab === 'products')
        @livewire('admin.products-manager', key('products-manager'))

      @elseif ($tab === 'categories')
        @livewire('admin.categories-manager', key('categories-manager'))

      @elseif ($tab === 'brands')
        @livewire('admin.brands-manager', key('brands-manager'))

      @elseif ($tab === 'customers')
        @livewire('admin.customers-manager', key('customers-manager'))

      @elseif ($tab === 'orders')
        @livewire('admin.orders-manager', key('orders-manager'))
      @endif
    </section>
  </div>

  {{-- Los scripts del componente VAN adentro del root usando @push --}}
  @push('scripts')
  <script>
    window.addEventListener('livewire:init', () => {
      Livewire.on('push-url', ({tab}) => {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tab);
        history.replaceState({}, '', url);
      });

      const hash = window.location.hash?.replace('#','');
      if (hash) {
        const comp = Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
        comp?.call('setTab', hash);
      }
    });
  </script>
  @endpush

</section> {{-- FIN ÚNICO ROOT --}}
