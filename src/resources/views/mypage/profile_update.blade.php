@extends('layouts.app')

@section('title', 'プロフィール更新')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile_update.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <h2 class="profile-title">プロフィール設定</h2>

    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="profile-image">
            <label for="image" class="profile-image-label">
                <img id="profile-preview"
                    src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) . '?t=' . time() : asset('images/default-avatar.png') }}"
                    alt="プロフィール画像">
            </label>
            <input type="file" name="image" id="image" accept="image/*">
            <label for="image" class="image-select-button">画像を選択する</label>
        </div>

        <div class="form-group">
            <label for="username">ユーザー名</label>
            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}">
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" value="{{ old('address', Auth::user()->address) }}">
        </div>

        <div class="form-group">
            <label for="building_name">建物名</label>
            <input type="text" name="building_name" value="{{ old('building_name', Auth::user()->building_name) }}">
        </div><br>

        <button type="submit" class="update-btn">更新する</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('profile-preview');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

@endpush('scripts')