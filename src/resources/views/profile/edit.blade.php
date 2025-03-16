@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')

<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<style>
    main {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
</style>


<div class="profile-container">
    <h2 class="profile-title">プロフィール設定</h2>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="profile-image-container">
            <label for="image" class="profile-image-label">
                <img id="profile-preview"
                    src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('images/default-avatar.png') }}"
                    alt="プロフィール画像">
            </label>
            <input type="file" name="image" id="image" accept="image/*">
            <button type="button" class="image-select-button" onclick="document.getElementById('image').click();">画像を選択する</button>
        </div>

        <div class="form-group">
            <label for="username">ユーザー名</label>
            <input type="text" name="username" id="username" value="{{ old('username', $user->username ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', $user->address ?? '') }}">
        </div>

        <div class="form-group">
            <label for="building_name">建物名</label>
            <input type="text" name="building_name" id="building_name" value="{{ old('building_name', $user->building_name ?? '') }}">
        </div><br>

        <button type="submit" class="update-btn">更新する</button>
    </form>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
