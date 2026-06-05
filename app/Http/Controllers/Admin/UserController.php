<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);        
        return view('admin.users.users-index', compact('users'));
    }

    public function create()
{
    return view('admin.users.users-create');
}

// Guardar el nuevo usuario
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'phone' => 'nullable|string|max:20',
        'document_type' => 'required|in:dni,ce',
        'document_number' => $request->document_type === 'dni'
            ? 'required|digits:8'
            : 'required|min:9|max:12',
        'address' => 'nullable|string|max:255',
        'role' => 'nullable|string',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'document_type' => $request->document_type,
        'document_number' => $request->document_number,
        'address' => $request->address,
        'role' => $request->role ?? 'customer',
    ]);

    if ($request->document_type === 'dni') {
        $request->validate([
            'document_number' => 'required|digits:8'
        ], [
        'document_number.digits' => 'El DNI debe tener 8 dígitos.',
        ]);
    } else {
        $request->validate([
        ], [
        'document_number.min' => 'El carnet de extranjería debe tener al menos 9 dígitos.',
        'document_number.max' => 'El carnet de extranjería no debe superar 12 dígitos.',
    ]);
    }

    return redirect()->route('admin.users.index')->with('ok', 'Usuario creado correctamente.');
}

    public function edit(User $user)
{
    return view('admin.users.users-edit', compact('user'));
}

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'document_type' => 'nullable|string',
            'document_number' => 'nullable|string|max:20',
            'role' => 'nullable|string',   
        ]);

        if ($request->document_type === 'dni') {
            $request->validate([
                'document_number' => 'required|digits:8'
            ], [
                'document_number.digits' => 'El DNI debe tener 8 dígitos.',
            ]);
        } else {
            $request->validate([
                'document_number' => 'required|min:9|max:12'
            ], [
                'document_number.min' => 'El carnet de extranjería debe tener al menos 9 dígitos.',
                'document_number.max' => 'El carnet de extranjería no debe superar 12 dígitos.',
            ]);
        }

        $user->update($request->only('name','email','phone','address','document_type','document_number','role'));

        return redirect()->route('admin.users.index')->with('ok', 'Cliente actualizado correctamente.');
    }


    public function destroy(User $user)
    {
        if ($user->role !== 'customer') {
            return back()->with('error', 'No puedes eliminar administradores.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('ok', 'Cliente eliminado correctamente.');
    }
}
