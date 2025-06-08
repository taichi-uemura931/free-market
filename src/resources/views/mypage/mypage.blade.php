@extends('layouts.app')

@section('title', 'マイページ')

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
        <a href="{{ route('mypage.edit') }}" class="edit-profile-button">プロフィールを編集</a>
    </div>

    <div class="tab-menu">
        <div class="tab-links">
            <a href="#sell-tab" class="tab active">出品した商品</a>
            <a href="#purchase-tab" class="tab">購入した商品</a>
            <a href="#transaction-tab" class="tab">取引中の商品</a>
        </div>
        <div class="tab-border"></div>
    </div>

    <div class="tab-content">
        <div id="sell-tab" class="tab-panel active">
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', ['id' => $product->id]) }}" class="product-image-link">
                            <div class="product-image-container">
                                <img src="{{ Storage::url($product->img_url) }}" class="product-image">
                            </div>
                            <div class="product-name">{{ $product->product_name }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="purchase-tab" class="tab-panel">
            <div class="product-grid">
                @foreach($purchasedProducts as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', ['id' => $product->id]) }}" class="product-image-link">
                            <div class="product-image-container">
                                <img src="{{ Storage::url($product->img_url) }}" class="product-image">
                            </div>
                            <div class="product-name">{{ $product->product_name }}</div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="transaction-tab" class="tab-panel">
            <div class="product-grid">
            @foreach($transactions as $transaction)
                <div class="product-card">
                    <a href="{{ route('chat.show', ['transaction' => $transaction->id]) }}" class="product-image-link">
                        <div class="product-image-container">
                            <img src="{{ Storage::url($transaction->product->img_url) }}" class="product-image">
                            @if($transaction->unread_messages_count > 0)
                                <span class="notification-dot">{{ $transaction->unread_messages_count }}</span>
                            @endif
                        </div>
                        <div class="product-name">{{ $transaction->product->product_name }}</div>
                    </a>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab');
    const panels = document.querySelectorAll('.tab-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const target = this.getAttribute('href');

            panels.forEach(panel => panel.classList.remove('active'));
            document.querySelector(target).classList.add('active');
        });
    });
});
</script>
@endpush

