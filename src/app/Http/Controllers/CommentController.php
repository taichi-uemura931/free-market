<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'comment_text' => 'required|string|max:255',
        ], [
            'comment_text.required' => 'コメントを入力してください',
            'comment_text.max' => 'コメントは255文字以下で入力してください',
        ]);


        Comment::create([
            'user_id' => Auth::user()->user_id,
            'product_id' => $product_id,
            'comment_text' => $request->comment_text,
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました');
    }
}
