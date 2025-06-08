<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    protected $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        $sessionShipping = session('shipping_address');

        if (is_array($sessionShipping) &&
            isset($sessionShipping['postal_code'], $sessionShipping['address'])) {
            $shippingAddress = $sessionShipping;
        } else {
            $shippingAddress = [
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building_name' => $user->building_name,
            ];
        }

        return view('products.purchase', compact('product', 'user', 'shippingAddress'));
    }

    public function process(Request $request, $productId)
    {
        $request->validate([
            'payment_method' => 'required|in:convenience_store,card',
        ], [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.in' => '有効な支払い方法を選択してください',
        ]);

        $shippingAddress = session('shipping_address');

        if (!$shippingAddress || !isset($shippingAddress['postal_code'], $shippingAddress['address'])) {
            return redirect()->back()->withErrors(['shipping_address' => '配送先情報が設定されていません']);
        }

        session(['selected_payment_method' => $request->payment_method]);

        $product = Product::findOrFail($productId);

        $paymentType = $request->payment_method === 'convenience_store' ? 'konbini' : 'card';

        $checkoutSession = $this->stripe->createCheckoutSession([
            'payment_method_types' => [$paymentType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $product->product_name],
                    'unit_amount' => $product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['id' => $productId]),
            'cancel_url' => route('stripe.cancel', ['id' => $productId]),
        ]);

        return redirect($checkoutSession->url);
    }

    public function stripeSuccess($product_id)
    {
        $product = Product::findOrFail($product_id);
        $user = Auth::user();

        $paymentMethod = session('selected_payment_method', 'card');

        $product->status = 'sold';
        $product->save();

        Order::create([
            'buyer_id' => $user->id,
            'product_id' => $product->id,
            'status' => 'completed',
            'payment_method' => $paymentMethod,
        ]);

        return redirect()->route('products.index')->with('success', 'カード決済が完了しました！');
    }

    public function stripeCancel($product_id)
    {
        return redirect()->route('purchase', ['id' => $product_id])
            ->with('error', '決済がキャンセルされました。もう一度お試しください。');
    }
}
