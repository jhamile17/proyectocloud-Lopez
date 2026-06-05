<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Lista de categorías
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.categories-index', compact('categories'));
    }

    // Formulario de creación
    public function create()
    {
        return view('admin.categories.categories-create');
    }

    // Guarda nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'description'));
        return redirect()->route('admin.categories.index')->with('ok', 'Categoría creada correctamente.');
    }

    // Formulario de edición
    public function edit(Category $category)
    {
        return view('admin.categories.categories-edit', compact('category'));
    }

    // Actualiza
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->categories_id . ',categories_id',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));
        return redirect()->route('admin.categories.index')->with('ok', 'Categoría actualizada correctamente.');
    }

    // Elimina
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('ok', 'Categoría eliminada correctamente.');
    }
}
