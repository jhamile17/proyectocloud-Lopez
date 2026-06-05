<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Stats (tal como lo tenías)
        $totalOrders = method_exists(\App\Models\Order::class, 'count')
            ? \App\Models\Order::where('user_id', $user->id)->count()
            : 0;

        $pendingOrders = method_exists(\App\Models\Order::class, 'count')
            ? \App\Models\Order::where('user_id', $user->id)->whereIn('status', ['pending', 'processing'])->count()
            : 0;

        $wishlistCount = method_exists(\App\Models\Wishlist::class, 'count')
            ? \App\Models\Wishlist::where('user_id', $user->id)->count()
            : 0;

        $recentOrders = class_exists(\App\Models\Order::class)
            ? \App\Models\Order::where('user_id', $user->id)->latest()->take(5)->get()
            : collect();

        $stats = [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'wishlistCount' => $wishlistCount,
        ];

        return view('customer.dashboard', compact('user', 'stats', 'recentOrders'));
    }

    
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();

       
        if (!empty($user->profile_photo_path)) {
            $oldPath = public_path($user->profile_photo_path);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file = $request->file('photo');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move(public_path('storage/uploads/avatars'), $filename);

        $user->profile_photo_path = 'storage/uploads/avatars/' . $filename;
        $user->save();

        return back()->with('success', 'Foto actualizada correctamente.');
    }
}
