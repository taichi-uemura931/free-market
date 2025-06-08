<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Message;

class ChatController extends Controller
{
    public function show($transactionId)
    {
        $user = Auth::user();

        $transaction = Transaction::with(['product', 'messages.sender'])->findOrFail($transactionId);

        if ($transaction->buyer_id !== $user->id && $transaction->seller_id !== $user->id) {
            abort(403);
        }

        $transactions = Transaction::with(['product', 'messages' => function ($q) {
            $q->latest();
        }])
        ->where(function ($q) use ($user) {
            $q->where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id);
        })
        ->whereHas('messages')
        ->withCount(['messages as unread_messages_count' => function ($q) use ($user) {
            $q->where('is_read', false)->where('sender_id', '!=', $user->id);
        }])
        ->orderByDesc('last_message_at')
        ->get()
        ->map(function ($t) {
            $t->latestMessage = $t->messages()->latest()->first();
            return $t;
        });

        foreach ($transaction->messages()->where('is_read', false)->where('sender_id', '!=', $user->id)->get() as $msg) {
            $msg->update(['is_read' => true]);
        }

        return view('chat.show', compact('transaction', 'transactions', 'user'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:400'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png'],
        ], [
            'content.required' => '本文を入力してください',
            'content.max' => '本文は400文字以内で入力してください',
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ]);

        $message = new Message();
        $message->transaction_id = $transaction->id;
        $message->sender_id = Auth::id();
        $message->content = $validated['content'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
            $message->image_path = $path;
        }

        $message->save();
        $transaction->update(['last_message_at' => now()]);

        return redirect()->route('chat.show', $transaction->id)->withInput();
    }
}