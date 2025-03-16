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
        <a href="{{ route('mypage.edit') }}" class="edit-profile-button">プロフィールを編集</a>
    </div>

    <div class="tab-menu">
        <div class="tab-links">
            <a href="#sell-tab" class="tab active">出品した商品</a>
            <a href="#purchase-tab" class="tab">購入した商品</a>
        </div>
        <div class="tab-border"></div>
    </div>

    <div class="tab-content">
    <div id="sell-tab" class="tab-panel active">
        <div class="product-grid">
            @forelse ($products as $product)
                @php $isSold = ($product->status === 'sold'); @endphp
                <div class="product-card {{ $isSold ? 'sold' : '' }}">
                    <div class="product-image-container">
                        @if ($isSold)
                            <div class="sold-label">SOLD</div>
                            <img src="{{ $product->img_url ? Storage::url($product->img_url) : asset('images/no-image.png') }}" class="product-image sold-image">
                        @else
                            <a href="{{ route('products.show', ['product_id' => $product->product_id]) }}" class="product-image-link">
                                <img src="{{ $product->img_url ? Storage::url($product->img_url) : asset('images/no-image.png') }}" class="product-image">
                            </a>
                        @endif
                    </div>
                    <div class="product-name">{{ $product->product_name }}</div>
                </div>
            @empty
                <p></p>
            @endforelse
        </div>
    </div>

    <div id="purchase-tab" class="tab-panel">
        <div class="product-grid">
            @forelse ($purchasedProducts as $product)
                @php $isSold = ($product->status === 'sold'); @endphp
                <div class="product-card {{ $isSold ? 'sold' : '' }}">
                    <div class="product-image-container">
                        @if ($isSold)
                            <div class="sold-label">SOLD</div>
                            <img src="{{ $product->img_url ? Storage::url($product->img_url) : asset('images/no-image.png') }}" class="product-image sold-image">
                        @else
                            <a href="{{ route('products.show', ['product_id' => $product->product_id]) }}" class="product-image-link">
                                <img src="{{ $product->img_url ? Storage::url($product->img_url) : asset('images/no-image.png') }}" class="product-image">
                            </a>
                        @endif
                    </div>
                    <div class="product-name">{{ $product->product_name }}</div>
                </div>
            @empty
                <p></p>
            @endforelse
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

            // タブのactiveクラス切り替え
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // 対応するパネルID取得
            const target = this.getAttribute('href');

            // パネルのactive切り替え
            panels.forEach(panel => panel.classList.remove('active'));
            document.querySelector(target).classList.add('active');
        });
    });
});
</script>
@endpush

