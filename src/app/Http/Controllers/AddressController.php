<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class AddressController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->query('product_id');
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
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string',
            'building_name' => 'required|string',
            'product_id' => 'required|integer',
        ], [
            'name.required' => 'お名前を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'address.required' => '住所を入力してください',
            'building_name.required' => '建物名を入力してください',
        ]);

        session([
            'shipping_address' => [
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building_name' => $request->building_name,
            ]
        ]);

        return redirect()->route('purchase', ['id' => $request->product_id]);
    }
}
