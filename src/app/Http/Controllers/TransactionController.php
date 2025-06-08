<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\Transaction;
use App\Mail\TransactionCompleted;

class TransactionController extends Controller
{
    public function start($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインしてください。');
        }

        $user = Auth::user();
        $product = Product::findOrFail($productId);

        if ($product->seller_id === $user->id) {
            return back()->with('error', '自分の出品商品とは取引できません。');
        }

        if ($product->status === 'sold') {
            return back()->with('error', 'この商品はすでに取引が完了しています。');
        }

        $existing = Transaction::where('product_id', $productId)
            ->where('buyer_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('chat.show', ['transaction' => $existing->id]);
        }

        $transaction = Transaction::create([
            'product_id' => $productId,
            'buyer_id' => $user->id,
            'seller_id' => $product->seller_id,
            'last_message_at' => now(),
        ]);

        return redirect()->route('chat.show', ['transaction' => $transaction->id]);
    }

    public function complete(Request $request, $transactionId)
    {
        $transaction = Transaction::with('product', 'buyer', 'seller')->findOrFail($transactionId);

        if (auth()->id() !== $transaction->buyer_id) {
            abort(403);
        }

        $transaction->is_completed = true;
        $transaction->save();

        Mail::to($transaction->seller->email)->send(new TransactionCompleted($transaction));

        return redirect()->route('chat.show', $transaction->id)
            ->with('success', '取引を完了しました。出品者に通知されました。');
    }
}
