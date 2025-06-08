<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Address;

class AddressController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->query('id');
        $product = Product::findOrFail($product_id);
        $sessionAddress = session('shipping_address');

        $shippingAddress = is_array($sessionAddress) ? $sessionAddress : [
            'name' => $user->username ?? $user->name ?? '',
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building_name' => $user->building_name,
        ];

        return view('address.edit', compact('product', 'shippingAddress'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'postal_code' => 'required|integer',
            'address' => 'required|string',
            'product_id' => 'required|integer',
        ], [
            'name.required' => 'お名前を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'address.required' => '住所を入力してください',
        ]);

        session([
            'shipping_address' => [
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building_name' => $request->building_name,
            ]
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building_name,
        ]);

        return redirect()->route('purchase', ['id' => $request->product_id]);
    }
}
