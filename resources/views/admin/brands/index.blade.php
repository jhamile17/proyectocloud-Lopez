@extends('layouts.admin')

@section('title', 'Marcas | PROCAFES')

@section('admin-content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Marcas</h1>
  <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
    + Nueva marca
  </a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          {{--<th>ID</th>--}}
          <th>Nombre</th>
          <th>Descripción</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($brands as $brand)
          <tr>
            {{-- muestra la PK real --}}
            {{--<td>{{ $brand->brand_id }}</td>--}}
            <td>{{ $brand->name }}</td>
            <td>{{ \Illuminate\Support\Str::limit($brand->description, 60) }}</td>
            <td class="text-end">
              <a href="{{ route('admin.brands.edit', ['brand' => $brand->getKey()]) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>

              <form action="{{ route('admin.brands.destroy', ['brand' => $brand->getKey()]) }}"
                    method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar marca?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Sin marcas registradas</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($brands, 'links'))
    <div class="card-body">
      {{ $brands->links() }}
    </div>
  @endif
</div>
@endsection
