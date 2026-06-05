<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->query('q', ''));
        $category = $request->query('category'); // id de categoría (PK real en categories)
        $brand    = $request->query('brand');    // id de marca (PK real en brands)
        $min      = $request->query('min');
        $max      = $request->query('max');
        $sort     = $request->query('sort', 'new');

        $products = Product::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                });
            })
            // Usa tus columnas reales en products
            ->when($category, fn ($qq) => $qq->where('categories_id', $category))
            ->when($brand,    fn ($qq) => $qq->where('brand_id',    $brand))
            ->when($min !== null && $min !== '', fn ($qq) => $qq->where('price', '>=', $min))
            ->when($max !== null && $max !== '', fn ($qq) => $qq->where('price', '<=', $max))
            ->when($sort === 'price_asc',  fn ($qq) => $qq->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($qq) => $qq->orderBy('price', 'desc'))
            ->when($sort === 'new',        fn ($qq) => $qq->latest())
            ->paginate(12)
            ->withQueryString();

        // categories/brands NO tienen 'id' como nombre de PK → alias a 'id' para la vista
        $categories = Category::selectRaw('categories_id as id, name')
            ->orderBy('name')
            ->get();

        $brands = Brand::selectRaw('brand_id as id, name')
            ->orderBy('name')
            ->get();

        return view('home', compact(
            'products', 'categories', 'brands', 'q', 'category', 'brand', 'min', 'max', 'sort'
        ));
    }
}
