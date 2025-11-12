@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibition.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
    <h2 class="content-title">商品の出品</h2>
    <div>
        <form action="{{ route('exhibition.store')}}" method="post" enctype="multipart/form-data">
            @csrf

            <h4 class="group-title">商品の画像</h4>
            <div class="image-box">
                <label for="img_url" class="image-button">画像を選択する</label>
                <input id="img_url" type="file" name="img_url" accept=".jpeg,.png">
            </div>
            @error('img_url')
                <p class="error-message">{{ $message }}</p>
            @enderror

            <h3 class="group-sub-title">商品の詳細</h3>

            <div class="category-select">
                <label for="category" class="group-title">カテゴリー</label>
                <div class="category-buttons">
                    @foreach($categories as $category)
                        <label class="category-button">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids',[])) ? 'checked' : '' }}>
                            <span>{{ $category->category }}</span>
                        </label>
                    @endforeach
                </div>
                @error('category_ids')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="condition-box">
                <label for="condition" class="group-title">商品の状態</label>
                <select name="condition_id">
                    <option value="" disabled hidden {{ old('condition_id') ? '' : 'selected' }}>選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ old('condition_id') == $condition->id ? '✓ ' : '' }}{{ $condition->condition }}
                        </option>
                    @endforeach
                </select>
                @error('condition_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="group-sub-title">商品名と説明</h3>
            <div class="group-box">
                <label for="name" class="group-title">商品名</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="group-box">
                <label for="brand_name" class="group-title">ブランド名</label>
                <input type="text" name="brand_name" value="{{ old('brand_name') }}">
            </div>

            <div class="group-box">
                <label for="detail" class="group-title">商品の説明</label>
                <textarea name="detail">{{ old('detail') }}</textarea>
                @error('detail')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="group-box">
                <label for="price" class="group-title">販売価格</label>
                <div class="price-input">
                    <input type="text" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="sell-button">出品する</button>
        </form>
    </div>
</div>
@endsection
