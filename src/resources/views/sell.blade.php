@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css')}}">
@endsection

@section('content')
<div class="content__wrapper">
    <h2 class="content__ttl">商品の出品</h2>
    <div>
        <form action="{{ route('sell.store')}}" method="post" enctype="multipart/form-data">
            @csrf

            <h4 class="group-ttl">商品の画像</h4>
            <div class="img__box">
                <label for="img_url" class="img__btn">画像を選択する</label>
                <input id="img_url" type="file" name="img_url" accept=".jpeg,.png">
            </div>
            @error('img_url')
                <p class="error">{{ $message }}</p>
            @enderror

            <h3 class="group__sub-ttl">商品の詳細</h3>

            <div class="category-select">
                <label for="category" class="group-ttl">カテゴリー</label>
                <div class="category-buttons">
                    @foreach($categories as $category)
                        <label class="category-btn">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_ids',[])) ? 'checked' : '' }}>
                            <span>{{ $category->category }}</span>
                        </label>
                    @endforeach
                    <!-- コスメ、アクセサリーで折り返すよう調整。上下はgap30pxでよさそう -->
                </div>
                @error('category_ids')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="condition__box">
                <label for="condition" class="group-ttl">商品の状態</label>
                <select name="condition_id">
                    <option value="" disabled hidden {{ old('condition_id') ? '' : 'selected' }}>選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}"{{ old( 'condition_id' ) == $condition->id ? 'selected' : '' }}>
                            {{ old('condition_id') == $condition->id ? '✓ ' : '' }}{{ $condition->condition }}
                        </option>
                        <!-- 同じ仕様に　$pay_method == 1 ? '✓ コンビニ払い' : 'コンビニ払い' -->
                        <!-- セレクトボックスを 680ｐｘ-->
                    @endforeach
                </select>
                @error('condition_id')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="group__sub-ttl">商品名と説明</h3>
            <div class="group__box">
                <label for="name" class="group-ttl">商品名</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="group__box">
                <label for="brand_name" class="group-ttl">ブランド名</label>
                <input type="text" name="brand_name" value="{{ old('brand_name') }}">
            </div>

            <div class="group__box">
                <label for="detail" class="group-ttl">商品の説明</label>
                <textarea name="detail">{{ old('detail') }}</textarea>
                @error('detail')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="group__box">
                <label for="price" class="group-ttl">販売価格</label>
                <div class="price-input">
                    <input type="text" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="sell__btn">出品する</button>
        </form>
    </div>
</div>
@endsection