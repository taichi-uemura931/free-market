@extends('layouts.app')

@section('title', 'マイリスト')

@section('content')

<link rel="stylesheet" href="{{ asset('css/products.css') }}">

<div class="container">

    <div class="tab-menu">
        <div class="tab-links">
            <a href="{{ route('products.index', ['query' => request('query')]) }}" class="tab {{ request()->routeIs('products.index') ? 'active' : '' }}">
                おすすめ
            </a>
            <a href="{{ route('products.mylist', ['query' => request('query')]) }}" class="tab {{ request()->routeIs('products.mylist') ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
        <div class="tab-border"></div>
    </div>

    <div class="product-grid">
        @foreach($products as $product)
                @php
                    $isSold = in_array($product->id, $soldProductIds);
                @endphp

            <div class="product-card {{ $isSold ? 'sold' : '' }}">
                <div class="product-image-container">
                    @if ($isSold)
                        <div class="sold-label">SOLD</div>
                        <img src="{{ $product->img_url ? Storage::url($product->img_url) : asset('images/no-image.png') }}" class="product-image sold-image">
                    @else
                        <a href="{{ route('products.show', ['id' => $product->id]) }}" class="product-image-link">
                            <img src="{{ $product->img_url ? Storage::url($product->img_url) : asset('images/no-image.png') }}" class="product-image">
                        </a>
                    @endif
                </div>
                <div class="product-name">{{ $product->product_name }}</div>
            </div>
        @endforeach
    </div>
</div>

@endsection
