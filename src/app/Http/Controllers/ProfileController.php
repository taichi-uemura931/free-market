<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインしてください。');
        }

        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->username = $request->input('username');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building_name = $request->input('building_name');

        if ($request->hasFile('image')) {
            if ($user->profile_image) {
                Storage::delete('public/' . $user->profile_image);
            }
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        $user->username = $request->username;
        $user->save();

        return redirect()->route('products.index')->with('success', 'プロフィールを更新しました');
    }
}
