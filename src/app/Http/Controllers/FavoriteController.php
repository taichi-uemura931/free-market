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

        if (Auth::check()) {
            $user = Auth::user();

            if ($product->seller_id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => '自分の出品にはいいねできません。',
                ], 403);
            }

            $favorite = $user->favorites()->where('product_id', $productId)->first();

            if ($favorite) {
                $favorite->delete();

                return response()->json([
                    'success' => true,
                    'liked' => false,
                    'favorites_count' => $product->favorites()->count(),
                ]);
            }

            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'liked' => true,
                'favorites_count' => $product->favorites()->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'ログインしてください。',
            ], 401);
        }
    }
}
