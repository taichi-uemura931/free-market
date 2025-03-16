@extends('layouts.app')

@section('title', '商品購入')

@section('content')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">

<div class="purchase-wrapper">
    <div class="purchase-left">
        <div class="product-info-box">
            <div class="product-image">
                <img src="{{ Storage::url($product->img_url) }}" alt="商品画像">
            </div>
            <div class="product-details">
                <h3 class="product-name">{{ $product->product_name }}</h3>
                <p class="product-price">¥{{ number_format($product->price) }}</p>
            </div>
        </div>

        <form action="{{ route('purchase.process', $product->product_id) }}" method="POST" class="purchase-form" id="purchase-form">
            @csrf

            <hr class="section-divider">
            <div class="payment-method-section">
                <h4 class="section-title">支払い方法</h4>
                <select name="payment_method" id="payment-method" class="payment-select" >
                    <option value="" selected disabled>選択してください</option>
                    <option value="convenience_store">コンビニ払い</option>
                    <option value="card">カード払い</option>
                </select>
            </div>

            <hr class="section-divider">
            <div class="address-section">
                <div class="address-header">
                    <h4 class="section-title">配送先</h4>
                    <a href="{{ route('address.edit', ['product_id' => $product->product_id]) }}" class="address-edit-link">変更する</a>
                </div>
                <p class="address-content">
                    〒{{ $shippingAddress['postal_code'] }}<br>
                    {{ $shippingAddress['address'] }}<br>
                    {{ $shippingAddress['building_name'] }}
                </p>
            </div>

            <hr class="section-divider">
        </form>
    </div>

    <div class="purchase-right">
        <div class="summary-card">
            <table class="summary-table">
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($product->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td><span id="payment-summary">選択してください</span></td>
                </tr>
            </table>
        </div>

        <div class="purchase-button-container">
            <button type="submit" class="purchase-button" form="purchase-form">購入する</button>
        </div>
    </div>

</div>

<script>
    document.getElementById('payment-method').addEventListener('change', function () {
        document.getElementById('payment-summary').textContent = this.options[this.selectedIndex].text;
    });
</script>
@endsection
