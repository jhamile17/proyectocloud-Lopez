@extends('layouts.admin')

@section('admin-content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Productos</h1>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
    + Nuevo producto
  </a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          {{--<th>ID</th>--}}
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Marca</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>stock_minimo</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $p)
          <tr>
            {{--<td>{{ $p->id }}</td>--}}
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name ?? '-' }}</td>
            <td>{{ $p->brand->name ?? '-' }}</td>
            <td>S/ {{ number_format($p->price, 2) }}</td>
            <td>{{ $p->stock }}</td>
            <td>{{ $p->stock_minimo }}</td>
            <td class="text-end">
              <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar producto?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">Sin productos</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($products, 'links'))
  <div class="card-body">
    {{ $products->onEachSide(1)->links('vendor.pagination.procafes') }}
  </div>
@endif

  </div>
</div>
@endsection
