<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Review;

class MypageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $products = Product::where('seller_id', $user->id)->get();

        $purchasedOrders = Order::with('product')
            ->where('buyer_id', $user->id)
            ->get();

        $purchasedProducts = $purchasedOrders->map(function ($order) {
            return $order->product;
        });

        $transactions = Transaction::with('product')
        ->where(function ($q) use ($user) {
            $q->where('buyer_id', $user->id)
                ->orWhere('seller_id', $user->id);
        })
        ->where('is_completed', false)
        ->withCount(['messages as unread_messages_count' => function ($q) use ($user) {
            $q->where('is_read', false)->where('sender_id', '!=', $user->id);
        }])
        ->get();

        $ongoingTransactions = Transaction::where('buyer_id', Auth::id())
        ->where('is_completed', false)
        ->get();

        return view('mypage.mypage', compact(
            'user', 'products', 'purchasedProducts', 'transactions','ongoingTransactions'
        ));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('mypage.profile_update', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $user->username = $request->username;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building_name = $request->building_name;

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->store('profile_images', 'public');
            $user->profile_image = $filename;
        }

        $user->save();

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }

    public function sellerPage($id)
    {
        $user = User::findOrFail($id);

        $products = Product::where('seller_id', $user->id)->get();

        $averageRating = Review::where('reviewee_id', $user->id)->avg('rating');
        $roundedRating = $averageRating ? round($averageRating, 1) : null;

        return view('mypage.seller_page', compact('user', 'products', 'roundedRating'));
    }
}
