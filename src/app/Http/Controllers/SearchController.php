<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        return view('purchase', compact('product', 'user'));
    }

    public function process(Request $request, $productId)
    {
        $request->validate([
            'payment_method' => 'required|in:convenience_store,card',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'buyer_id'   => Auth::user()->user_id,
                'product_id' => $productId,
                'status'     => 'completed',
            ]);

            $payment = Payment::create([
                'order_id'        => $order->order_id,
                'payment_method'  => $request->payment_method,
                'payment_status'  => 'paid',
            ]);

            $order->payment_id = $payment->payment_id;
            $order->save();

            $product = Product::findOrFail($productId);
            $product->sale_status = 'sold';
            $product->save();

            DB::commit();

            if ($request->payment_method === 'card') {
                return redirect()->route('stripe.checkout');
            }

            return redirect()->route('mypage')->with('success', '購入が完了しました');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', '購入処理中にエラーが発生しました');
        }
    }
}

