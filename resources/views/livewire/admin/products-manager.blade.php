<section> {{-- ÚNICO ROOT DEL COMPONENTE --}}

  {{-- Filtros / acciones --}}
  <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-3">
    <div class="d-flex gap-2">
      <input type="text" class="form-control" placeholder="Buscar producto..." style="min-width:260px"
             wire:model.debounce.400ms="q">
      <select class="form-select" wire:model="status">
        <option value="">Todos</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
      </select>
      <select class="form-select" wire:model="perPage">
        <option>10</option><option>25</option><option>50</option>
      </select>
    </div>

    <button class="btn btn-primary" wire:click="create">
      <i class="bi bi-plus-lg me-1"></i> Nuevo producto
    </button>
  </div>

  {{-- Alerts --}}
  @if(session('success'))
    <div class="alert alert-success py-2">{{ session('success') }}</div>
  @endif

  {{-- Tabla --}}
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Marca</th>
          <th class="text-end">Precio</th>
          <th class="text-end">Stock</th>
          <th>Estado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
      @forelse($products as $p)
        <tr>
          <td>
            <img src="{{ $p->image ? Storage::url($p->image) : 'https://via.placeholder.com/44' }}"
                 class="rounded" width="44" height="44" alt="">
          </td>
          <td class="fw-semibold">{{ $p->name }}</td>
          <td>{{ $p->category->name ?? '—' }}</td>
          <td>{{ $p->brand->name ?? '—' }}</td>
          <td class="text-end">S/ {{ number_format($p->price,2) }}</td>
          <td class="text-end">{{ $p->stock }}</td>
          <td>
            @php $active = (bool)($p->status ?? $p->is_active ?? 1); @endphp
            <span class="badge {{ $active ? 'text-bg-success' : 'text-bg-secondary' }}">
              {{ $active ? 'Active' : 'Inactivo' }}
            </span>
          </td>
          <td class="text-end">
            <div class="btn-group btn-group-sm">
              <button class="btn btn-warning" wire:click="edit({{ $p->id }})">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-danger" wire:click="confirmDelete({{ $p->id }})">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center py-4 text-muted">Sin resultados…</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">
    {{ $products->links('pagination::bootstrap-5') }}
  </div>

  {{-- Modal Crear/Editar --}}
  <div class="modal fade" id="productModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form wire:submit.prevent="store">
          <div class="modal-header">
            <h5 class="modal-title">{{ $product_id ? 'Editar producto' : 'Nuevo producto' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-4">
                <label class="form-label">Precio (S/)</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" wire:model.defer="price">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control @error('stock') is-invalid @enderror" wire:model.defer="stock">
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select class="form-select" wire:model.defer="is_active">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <select class="form-select @error('category_id') is-invalid @enderror" wire:model.defer="category_id">
                  <option value="">-- Seleccionar --</option>
                  @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                  @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Marca</label>
                <select class="form-select @error('brand_id') is-invalid @enderror" wire:model.defer="brand_id">
                  <option value="">-- Ninguna --</option>
                  @foreach($brands as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                  @endforeach
                </select>
                @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label class="form-label">Imagen (ruta)</label>
                <input type="text" class="form-control @error('image') is-invalid @enderror" wire:model.defer="image"
                       placeholder="storage/uploads/products/xxx.jpg">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
            <button class="btn btn-primary" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Confirmar eliminación --}}
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas eliminar este producto? Esta acción no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Cancelar</button>
          <button class="btn btn-danger" wire:click="destroy" type="button">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

</section> {{-- FIN ÚNICO ROOT --}}

@push('scripts')
<script>
  // hooks para abrir/cerrar modales desde Livewire
  window.addEventListener('livewire:init', () => {
    Livewire.on('open-modal', sel => new bootstrap.Modal(document.querySelector(sel)).show());
    Livewire.on('close-modal', sel => bootstrap.Modal.getInstance(document.querySelector(sel))?.hide());
  });
</script>
@endpush
