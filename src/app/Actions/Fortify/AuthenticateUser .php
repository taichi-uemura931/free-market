<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    /**
     * ログイン処理
     */
    public function authenticate(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        // ユーザーの検索と認証
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません。'],
            ]);
        }

        return $user;
    }
}
