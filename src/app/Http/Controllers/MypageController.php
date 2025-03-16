<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class MypageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $products = Product::where('seller_id', Auth::id())->get();

        $orders = Order::with('product')->where('buyer_id', $user->id)->get();
        $purchasedProducts = Order::with('product')
            ->where('buyer_id', $user->user_id)
            ->get()
            ->map(function ($order) {
                return $order->product;
            });

        return view('mypage.mypage', compact('user', 'products', 'purchasedProducts'));
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
}
