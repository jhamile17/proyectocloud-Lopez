<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Detectar columnas dinámicamente =====
        $brandPk = $this->pickColumn('brands', ['id','brand_id','brands_id']);
        $catPk   = $this->pickColumn('categories', ['id','category_id','categories_id']);

        $prodBrandFk = $this->pickColumn('products', ['brand_id','brands_id','brand','brandId']);
        $prodCatFk   = $this->pickColumn('products', ['category_id','categories_id','category','categoryId']);

        $hasBrandSlug = Schema::hasColumn('brands', 'slug');
        $hasCatSlug   = Schema::hasColumn('categories', 'slug');
        $hasProdSlug  = Schema::hasColumn('products', 'slug');

        // ===== MARCAS =====
        $brands = ['PROCAFES', 'Andes Coffee', 'Selva Alta', 'Chanchamayo'];
        foreach ($brands as $b) {
            $data = [
                'name'       => $b,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasBrandSlug) {
                $data['slug'] = Str::slug($b);
            }
            DB::table('brands')->updateOrInsert(['name' => $b], $data);
        }

        // ===== CATEGORÍAS =====
        $cats = ['Grano', 'Molido', 'Cápsulas', 'Accesorios'];
        foreach ($cats as $c) {
            $data = [
                'name'       => $c,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($hasCatSlug) {
                $data['slug'] = Str::slug($c);
            }
            DB::table('categories')->updateOrInsert(['name' => $c], $data);
        }

        // Mapear PKs (puede fallar si no existen columnas)
        $brandsMap = DB::table('brands')->pluck($brandPk, 'name');      // name => pk
        $catsMap   = DB::table('categories')->pluck($catPk, 'name');    // name => pk

        // ===== PRODUCTOS =====
        $prods = [
            ['name'=>'Café Grano Especial 250g',   'price'=>22.90, 'brand'=>'PROCAFES','category'=>'Grano'],
            ['name'=>'Café Grano Gourmet 500g',    'price'=>39.90, 'brand'=>'Selva Alta','category'=>'Grano'],
            ['name'=>'Café Molido Intenso 250g',   'price'=>21.50, 'brand'=>'Andes Coffee','category'=>'Molido'],
            ['name'=>'Café Molido Suave 250g',     'price'=>19.90, 'brand'=>'Andes Coffee','category'=>'Molido'],
            ['name'=>'Cápsulas Espresso x10',      'price'=>27.00, 'brand'=>'Chanchamayo','category'=>'Cápsulas'],
            ['name'=>'Prensa Francesa 350ml',      'price'=>49.00, 'brand'=>'PROCAFES','category'=>'Accesorios'],
            ['name'=>'V60 Filtro #2 (x100)',       'price'=>24.00, 'brand'=>'PROCAFES','category'=>'Accesorios'],
            ['name'=>'Café Grano Orgánico 1kg',    'price'=>74.90, 'brand'=>'Selva Alta','category'=>'Grano'],
        ];

        foreach ($prods as $p) {
            if (DB::table('products')->where('name', $p['name'])->exists()) {
                continue;
            }

            $data = [
                'name'       => $p['name'],
                'price'      => $p['price'],
                'stock'      => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // FK dinámicas si existen en la tabla products
            if ($prodBrandFk && isset($brandsMap[$p['brand']])) {
                $data[$prodBrandFk] = $brandsMap[$p['brand']];
            }
            if ($prodCatFk && isset($catsMap[$p['category']])) {
                $data[$prodCatFk] = $catsMap[$p['category']];
            }

            if ($hasProdSlug) {
                $data['slug'] = Str::slug($p['name']);
            }

            DB::table('products')->insert($data);
        }
    }

    private function pickColumn(string $table, array $candidates): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }
        return null;
    }
}
