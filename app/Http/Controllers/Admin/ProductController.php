<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(10);

        return view('admin.products.products-index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        return view('admin.products.products-create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['required', 'integer', 'min:0'],
            'categories_id' => ['required', Rule::exists('categories', 'categories_id')],
            'brand_id'      => ['nullable', Rule::exists('brands', 'brand_id')],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description'   => ['nullable', 'string'],
        ]);

        // ✅ Define estado automáticamente
        $validated['status'] = $validated['stock'] > 0 ? 1 : 0;

        // Normaliza el precio
        $validated['price'] = (float) str_replace(',', '.', $validated['price']);

        // Sube imagen
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads/products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('ok', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        return view('admin.products.products-edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['required', 'integer', 'min:0'],
            'categories_id' => ['required', Rule::exists('categories', 'categories_id')],
            'brand_id'      => ['nullable', Rule::exists('brands', 'brand_id')],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description'   => ['nullable', 'string'],
        ]);

        $validated['status'] = $validated['stock'] > 0 ? 1 : 0;

        // Normaliza el precio
        $validated['price'] = (float) str_replace(',', '.', $validated['price']);

        // Reemplaza imagen si hay una nueva
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('uploads/products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('ok', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('ok', 'Producto eliminado correctamente.');
    }
}
