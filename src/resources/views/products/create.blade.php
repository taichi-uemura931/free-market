@extends('layouts.app')

@section('title', '商品出品')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product_create.css') }}">
@endpush

@section('content')
<div class="container">
    <h2>商品の出品</h2>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="section-title">商品画像</label>
        <div class="image-upload-box">
            <label for="imageInput" class="image-select-button">画像を選択する</label>
            <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png" style="display:none">
            <div id="imagePreview"></div>
        </div>
        @error('image')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <h2 class="section-heading">商品の詳細</h2>
        <hr class="section-divider">

        <label class="section-title">カテゴリー</label>
            <div class="category-group">
                @foreach($categories as $category)
                    <label class="category-checkbox {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        <br>
        @error('categories')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <label class="section-title">商品の状態</label><br>
        <select name="condition">
            <option value="">選択してください</option>
            <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
            <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
            <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
        </select><br>
        @error('condition')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <h2 class="section-heading">商品名と説明</h2>
        <hr class="section-divider">

        <label class="section-title">商品名</label><br>
        <input type="text" name="product_name" value="{{ old('product_name') }}">
        <br>
        @error('product_name')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <label class="section-title">ブランド名</label><br>
        <input type="text" name="brand_name" value="{{ old('brand_name') }}"><br>
        @error('brand_name')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <label class="section-title">商品説明</label><br>
        <textarea name="description" rows="6">{{ old('description') }}</textarea>
        <br>
        @error('description')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <label class="section-title">販売価格</label><br>
        <input type="text" name="price" value="{{ old('price') }}"><br>
        @error('price')
            <div class="error">{{ $message }}</div>
        @enderror<br>

        <button type="submit" class="btn-submit">出品する</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.category-checkbox input[type="checkbox"]');
        checkboxes.forEach((checkbox) => {
            const label = checkbox.parentElement;
            checkbox.addEventListener('change', function () {
                label.classList.toggle('checked', checkbox.checked);
            });
        });

        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function () {
            imagePreview.innerHTML = '';
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.display = 'block';
                    imagePreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush

