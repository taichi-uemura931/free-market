@extends('layouts.app')

@section('title', '商品詳細')

@section('content')

<link rel="stylesheet" href="{{ asset('css/product_detail.css') }}">

<div class="container">
    <div class="product-detail">
        <div class="product-image-slider">
            @if ($product->img_url && file_exists(public_path('storage/products/' . basename($product->img_url))))
                <img src="{{ Storage::url($product->img_url) }}" alt="{{ $product->product_name }}" class="product-image">
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="画像なし">
            @endif
        </div>

        <div class="product-info">
            <h2>{{ $product->product_name }}</h2>
            <p class="seller-profile">
                @if(Auth::id() !== $product->seller_id)
                    <p class="seller-profile">
                        <a href="{{ route('seller.page', $product->seller_id) }}">
                            {{ $product->seller->username }}
                        </a>
                    </p>
                @endif
            </p>
            <p class="brand">{{ $product->brand_name ?? '' }}</p>
            <p class="price">
            ¥{{ number_format($product->price) }} <span class="tax-label">（税込）</span></p>

            <div class="actions">
                <span class="like-icon
                    @if(Auth::check() && $product->isLikedBy(Auth::user()))
                        liked
                    @elseif(!Auth::check() && $product->isLikedByGuest())
                        liked
                    @endif"
                    data-product-id="{{ $product->id }}">
                    @if(Auth::check())
                        {{ $product->isLikedBy(Auth::user()) ? '♥' : '♡' }}
                    @else
                        {{ $product->isLikedByGuest() ? '♥' : '♡' }}
                    @endif
                </span>
                <span id="like-count">{{ $product->favorites->count() }}</span>

                <span class="comment-icon">💬</span>
                <span id="comment-count">{{ $product->comments->count() }}</span>
            </div>

            @auth
                @if ($product->status === 'sold')
                    <p class="buy-button disabled">この商品は既に購入済みです</p>
                @elseif ($product->seller_id === Auth::id())
                    <p class="buy-button disabled">あなたの出品商品です</p>
                @else
                    <a href="{{ route('purchase', ['id' => $product->id]) }}" class="buy-button">購入手続きへ</a>
                    @if ($transaction && $transaction->is_completed)
                        <p class="transaction-button disabled" style="margin-top: 10px;">この出品者との取引は終了しました</p>
                    @else
                        <a href="{{ route('transaction.start', $product->id) }}" class="transaction-button" style="margin-top: 10px;">この出品者と取引チャットを開始</a>
                    @endif

                @endif
            @else
                <a href="{{ route('login') }}" class="buy-button">ログインして購入</a>
            @endauth

            <div class="product-description">
                <h3>商品説明</h3>
                <p>{{ $product->description }}</p>
            </div>

            <div class="product-meta">
                <h3>商品の情報</h3><br>
                <div class="info-row">
                    <p><strong>カテゴリー</strong></p>
                    <div class="category-tags">
                        @foreach($product->categories as $category)
                            <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div><br>

                <div class="info-row">
                    <p><strong>商品の状態</strong></p>
                    <span class="condition-label">{{ $product->condition }}</span>
                </div>
            </div>

            <div class="comments-section">
                <h3>コメント ({{ $product->comments->count() }})</h3>

                <div class="comments-list">
                    @foreach($product->comments as $comment)
                        <div class="comment">
                            <div class="comment-header">
                                @if($comment->user->profile_image)
                                    <img src="{{ $comment->user->profile_image_url }}" class="user-icon-img" alt="ユーザーアイコン">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" class="user-icon-img" alt="デフォルトアイコン">
                                @endif
                                <span class="user-name">{{ $comment->user->username }}</span>
                            </div>
                            <p class="comment-text">{{ $comment->comment_text }}</p>
                        </div>
                    @endforeach
                </div>

                @auth
                    <h3>商品へのコメント</h3>

                    <form action="{{ route('comment.store', ['id' => $product->id]) }}" method="POST" novalidate>
                        @csrf
                        <textarea name="comment_text" class="comment-box">{{ old('comment_text') }}</textarea>

                        @error('comment_text')
                            <p style="color: red; font-size: 14px; margin-top: 4px;">{{ $message }}</p>
                        @enderror

                        <div class="comment-button-container">
                            <button type="submit" class="comment-submit">コメントを送信する</button>
                        </div>
                    </form>
                    @else
                        <p>コメントを投稿するには<a href="{{ route('login') }}">ログイン</a>してください。</p>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const likeIcon = this;
            const likeCountElement = document.getElementById('like-count');

            fetch(`/favorite/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeIcon.textContent = '♥';
                    likeIcon.classList.add('liked');
                    likeCountElement.textContent = data.favorites_count;
                    likeIcon.classList.add('disabled');
                    likeIcon.style.pointerEvents = 'none';
                } else {
                    if (data.message === 'すでにいいね済みです。') {
                        alert(data.message);
                        likeIcon.classList.add('disabled');
                        likeIcon.style.pointerEvents = 'none';
                    } else {
                        alert(data.message || "エラーが発生しました。");
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

@endsection
