@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')
    <div class="container">
        <h2 class="text-center my-4">プロフィール設定</h2>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', Auth::user()->username) }}" required>
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}">
                @error('postal_code')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', Auth::user()->address) }}">
                @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="building_name">建物名</label>
                <input type="text" class="form-control @error('building_name') is-invalid @enderror" name="building_name" value="{{ old('building_name', Auth::user()->building_name) }}">
                @error('building_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-danger mt-3">更新する</button>
        </form>
    </div>
@endsection