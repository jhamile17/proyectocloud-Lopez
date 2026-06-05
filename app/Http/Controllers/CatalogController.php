<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogController extends Controller
{
    public function home(){
        $products = Product::where('status','active')->latest('products_id')->paginate(12);
        return view('catalog.home', compact('products'));
    }

    public function product($slug){
        $product = Product::where('slug',$slug)->firstOrFail();
        return view('catalog.product', compact('product'));
    }
}
