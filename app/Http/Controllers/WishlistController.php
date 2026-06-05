<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Página "Mis favoritos"
    public function index()
    {
        $items = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('items'));
    }

    // Añadir con form clásico: POST /wishlist/add/{product}
    public function store(Product $product)
    {
        $userId = Auth::id();

        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Este producto ya está en tu lista de deseos.');
        }

        Wishlist::create([
            'user_id'    => $userId,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Producto agregado a tu lista de deseos.');
    }

    // Eliminar (si la usas): DELETE /wishlist/remove/{product}
    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Producto quitado de tu lista de deseos.');
    }

    // Toggle AJAX opcional: POST /wishlist/toggle  (body: {product_id})
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
        ]);

        $userId = Auth::id();
        $pid = (int) $request->product_id;

        $row = Wishlist::where('user_id', $userId)->where('product_id', $pid)->first();
        if ($row) {
            $row->delete();
            $added = false;
        } else {
            Wishlist::create(['user_id'=>$userId,'product_id'=>$pid]);
            $added = true;
        }

        $count = Wishlist::where('user_id', $userId)->count();

        return response()->json(['ok'=>true,'added'=>$added,'count'=>$count,'product_id'=>$pid]);
    }
}
