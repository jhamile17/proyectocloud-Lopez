@extends('layouts.admin')

@section('admin-content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Categorías</h1>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
    + Nueva categoría
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
        @forelse($categories as $cat)
          <tr>
            {{--<td>{{ $cat->categories_id }}</td>--}}
            <td>{{ $cat->name }}</td>
            <td>{{ Str::limit($cat->description, 60) }}</td>
            <td class="text-end">
              <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar categoría?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Sin categorías</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($categories, 'links'))
  <div class="card-body">
    {{ $categories->onEachSide(1)->links('vendor.pagination.procafes') }}
  </div>
@endif

  </div>
</div>
@endsection
