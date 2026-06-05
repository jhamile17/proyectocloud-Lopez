@extends('layouts.admin')

@section('title', 'Usuarios | PROCAFES')

@section('admin-content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Usuarios</h1>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
    + Nuevo usuario
  </a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          {{--<th>ID</th>--}}
          <th>Nombre</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Tipo de documento</th>
          <th>Número de documento</th>
          <th>Dirección</th>
          <th>Rol</th>
          <th>Verificado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr>
            {{--<td>{{ $u->id }}</td>--}}
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->phone }}</td>
            <td>{{ $u->document_type }}</td>
            <td>{{ $u->document_number }}</td>
            <td>{{ $u->address }}</td>
            <td>
              <span class="badge {{ $u->role == 'admin' ? 'text-bg-danger' : 'text-bg-secondary' }}">
                {{ ucfirst($u->role) }}
              </span>
            </td>
            <td>
              @if($u->email_verified_at)
                <span class="badge text-bg-success">Sí</span>
              @else
                <span class="badge text-bg-warning text-dark">No</span>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar usuario?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Sin usuarios</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($users, 'links'))
    <div class="card-body">
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection