@extends('layouts.app')

@section('title', '出品者情報')

@section('content')

<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">

<div class="container">
    <div class="profile-header">
        <div class="profile-info">
            <div class="profile-image">
                @if(Auth::user()->profile_image)
                    <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="プロフィール画像">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="デフォルトアイコン">
                @endif
            </div>
            <h2 class="username">{{ $user->username }}</h2>
        </div>
        <p class="user-rating">
            @if($user->reviewsReceived && $user->reviewsReceived->count() > 0)
                評価 &nbsp;&nbsp;☆&nbsp;{{ $user->averageRating() }}/5（{{ $user->reviewsReceived->count() }}件）
            @else
            @endif
        </p>
    </div>

    <div class="tab-menu">
        <div class="tab-links">
            <a class="tab active">出品した商品</a>
        </div>
        <div class="tab-border"></div>
    </div>

    <div class="product-grid">
        @foreach($products as $product)
            <div class="product-card">
                <a href="{{ route('products.show', $product->id) }}" class="product-image-link">
                    <div class="product-image-container">
                        <img src="{{ Storage::url($product->img_url) }}" class="product-image">
                    </div>
                    <div class="product-name">{{ $product->product_name }}</div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
