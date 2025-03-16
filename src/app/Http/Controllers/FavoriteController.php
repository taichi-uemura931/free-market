<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class FavoriteController extends Controller {

    public function toggle($productId)
    {
        $product = Product::findOrFail($productId);
        $liked = false;

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->favorites()->where('product_id', $productId)->exists()) {
                $user->favorites()->where('product_id', $productId)->delete();
            } else {
                Favorite::create([
                    'user_id' => $user->user_id,
                    'product_id' => $productId,
                ]);
                $liked = true;
            }

        } else {
            $favorites = session()->get('guest_favorites', []);

            if (in_array($productId, $favorites)) {
                $favorites = array_diff($favorites, [$productId]);
                session()->put('guest_favorites', $favorites);
            } else {
                $favorites[] = $productId;
                session()->put('guest_favorites', $favorites);
                $liked = true;
            }
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'favorites_count' => $product->favorites()->count() + (Auth::check() ? 0 : (in_array($productId, session()->get('guest_favorites', [])) ? 1 : 0))
        ]);
    }
}
