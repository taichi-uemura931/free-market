<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class MessageController extends Controller
{
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $this->authorize('update', $message);

        $request->validate([
            'content' => 'required|string|max:400|min:1',
        ]);

        $message->content = $request->input('content');
        $message->save();

        return response()->json(['message' => '更新しました。']);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();
        return response()->json(['message' => '削除しました。']);
    }
}
