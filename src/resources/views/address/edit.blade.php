@extends('layouts.app')

@section('title', '配送先変更')

@section('content')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">

<div class="address-container">
    <h2>住所の変更</h2>

    <form action="{{ route('address.update') }}" method="POST" class="address-form">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->product_id }}">

        <label>お名前</label>
        <input type="text" name="name" value="{{ old('name', $shippingAddress['name'] ?? '') }}">
        @error('name') <div class="error-message">{{ $message }}</div> @enderror

        <label for="postal_code">郵便番号</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $shippingAddress['postal_code'] ?? '') }}">
        @error('postal_code') <div class="error-message">{{ $message }}</div> @enderror

        <label for="address">住所</label>
        <input type="text" name="address" id="address" value="{{ old('address', $shippingAddress['address'] ?? '') }}">
        @error('address') <div class="error-message">{{ $message }}</div> @enderror

        <label for="building_name">建物名</label>
        <input type="text" name="building_name" id="building_name" value="{{ old('building_name', $shippingAddress['building_name'] ?? '') }}">
        @error('building_name') <div class="error-message">{{ $message }}</div> @enderror

        <button type="submit" class="address-submit">更新する</button>
    </form>
</div>
@endsection
