<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        // Ordena por la columna de la PK (id por defecto) descendente
        $brands = Brand::query()->orderByDesc((new Brand)->getKeyName())->paginate(10);

        // Vista limpia y unificada
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required','string','max:255','unique:brands,name'],
            'description' => ['nullable','string'],
        ]);

        Brand::create($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('ok', 'Marca creada correctamente.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        // Detecta PK y nombre de PK del modelo (soporta id, brands_id, etc.)
        $pkName = $brand->getKeyName();
        $pk     = $brand->getKey();

        $validated = $request->validate([
            'name'        => [
                'required','string','max:255',
                Rule::unique('brands', 'name')->ignore($pk, $pkName),
            ],
            'description' => ['nullable','string'],
        ]);

        $brand->update($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('ok', 'Marca actualizada correctamente.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('ok', 'Marca eliminada correctamente.');
    }
}
