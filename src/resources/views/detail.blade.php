@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product__wrapper">
    <div class="product__img">
        <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->name }}">
    </div>

    <div class="product__box">
        <!-- 中央ぞろえにしたい -->
        <div class="product__inner">
            <h2 class="product__name">{{ $product->name }}</h2>
            <p class="product__brand">{{ $product->brand_name }}</p>
            <!-- ブランド名なしでも幅は保持したい -->
            <p class="product__price">{{ number_format($product->price) }}</p>
            <!-- ￥と　税込みは小さく -->

        <div class="icon-wrapper">
            <form action="/item/{{ $product->id }}/like" method="post" class="like-form">
                @csrf
                <button type="submit" class="like-button @if(Auth::check() && Auth::user()->mylists->contains($product->id)) liked @endif">
                    <img src="{{ asset('storage/products_images/like.png') }}" alt="いいね" class="like-icon">
                    <span class="like-count">{{ $product->mylists_count }}</span>
                </button>
            </form>

            <div class="comment__icon-box">
                <img src="{{ asset('storage/products_images/comment.png') }}" alt="" class="comment__icon">
                <span class="comment-count">{{ $product->comments->count() }}</span>
            </div>
        </div>
        <!-- アイコンと数字を縦ならび -->
    </div>

    @if(!$product->purchase)
    <div class="product__buy">
        <a class="product__buy-btn" href="{{ route('purchases.show', $product->id) }}">購入手続きへ</a>
    </div>
    @endif

    <div class="product__inner">
        <h3 class="product__ttl">商品説明</h3>
        <p class="product__detail">{{ $product->detail }}</p>
        <!-- 改行や空白を保持させたい -->
    </div>

    <div class="product__inner">
        <h3 class="product__ttl">商品の情報</h3>

        <div class="category__wrapper">
            <h4 class="product__sub-ttl">カテゴリー</h4>
            <p class="product__category">
                @foreach($product->categories as $category)
                    <span>{{ $category->category }}</span>
                @endforeach
            </p>
            <!-- 一個にまとまって表示される -->
        </div>

        <div class="condition__wrapper">
            <h4 class="product__sub-ttl">商品の状態</h4>
            <p class="product__condition">{{ $product->condition->condition }}</p>
        </div>
    </div>

    <div class="product__inner">
        <h3 class="comment__ttl">コメント({{ $product->comments->count() }})</h3>
        <!-- 縦中央ぞろえにしたい -->
        @foreach ($product->comments as $comment)
            <div class="comment__wrapper">
                <div class="comment__user">
                    <div class="comment__user-img">
                        @if(!empty($comment->user->img_url))
                            <img src="{{ asset('storage/' . $comment->user->img_url) }}" alt="">
                        @else
                            <div class="user-placeholder"></div>
                        @endif
                    </div>
                    <h4 class="comment__user-name">{{ $comment->user->name }}</h4>
                    <!-- user-imgに対して中央に配置したい -->
                </div>
                <p class="comment__content">{{ $comment->comment }}</p>
            </div>
        @endforeach

            <h3 class="product-comment__ttl">商品へのコメント</h3>
            <div class="comment__box">
                <form  action="{{ route('comment.store',$product->id) }}" method="post">
                    @csrf
                    <textarea name="content"></textarea>
                    @error('content')
                        <p class="content__error">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="comment__btn">コメントを送信する</button>
                </form>
            </div>
            <!-- 上記を縦並びにしたい -->
        </div>
    </div>
</div>
@endsection