@extends('layouts.app')

@section('title', '商品一覧')

@section('content')

<link rel="stylesheet" href="{{ asset('css/products.css') }}">

<div class="container">

    <div class="tab-menu">
        <div class="tab-links">
            <a href="{{ route('products.index', ['query' => request('query')]) }}"
                class="tab {{ request()->routeIs('products.index') ? 'active' : '' }}">おすすめ</a>
            @auth
                <a href="{{ route('products.mylist') }}"
                    class="tab {{ request()->routeIs('products.mylist') ? 'active' : '' }}">マイリスト</a>
            @endauth
        </div>
        <div class="tab-border"></div>
    </div>

    <div id="purchase-tab" class="tab-panel">
        <div class="product-grid">
        @foreach($products as $product)
            @php
                $isSold = ($product->status === 'sold');
            @endphp

            <div class="product-card {{ $isSold ? 'sold' : '' }}">
                <div class="product-image-container">
                    @if ($isSold)
                        <div class="sold-label">SOLD</div>
                    @endif
                    <a href="{{ route('products.show', ['id' => $product->id]) }}">
                        <img src="{{ Storage::url($product->img_url) }}" class="product-image {{ $isSold ? 'sold-image' : '' }}">
                    </a>
                </div>
                <div class="product-name">{{ $product->product_name }}</div>
            </div>
        @endforeach
        </div>
    </div>
</div>

@endsection
