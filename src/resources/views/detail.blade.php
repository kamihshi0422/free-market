@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product-wrapper">
    <div class="product-img">
        <img src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->name }}">
    </div>

    <div class="product-box">
        <div class="product-inner">
            <h2 class="product-name">{{ $product->name }}</h2>
            <p class="product-brand">{{ $product->brand_name }}</p>
            <p class="product-price">{{ number_format($product->price) }}</p>

            <div class="icon-wrapper">
                <form action="/item/{{ $product->id }}/like" method="post" class="like-form">
                    @csrf
                    <button type="submit" class="like-button @if(Auth::check() && Auth::user()->mylists->contains($product->id)) liked @endif">
                        <img src="{{ asset('storage/products_images/like.png') }}" alt="いいね" class="like-icon">
                        <span class="like-count">{{ $product->mylists_count }}</span>
                    </button>
                </form>

                <div class="comment-icon-box">
                    <img src="{{ asset('storage/products_images/comment.png') }}" alt="" class="comment-icon">
                    <span class="comment-count">{{ $product->comments->count() }}</span>
                </div>
            </div>
        </div>

        @if(!$product->purchase)
        <div class="product-buy">
            <a class="product-buy-btn" href="{{ route('purchase.show', $product->id) }}">購入手続きへ</a>
        </div>
        @endif

        <div class="product-inner">
            <h3 class="product-title">商品説明</h3>
            <p class="product-detail">{{ $product->detail }}</p>
        </div>

        <div class="product-inner">
            <h3 class="product-title">商品の情報</h3>

            <div class="category-wrapper">
                <h4 class="product-sub-title">カテゴリー</h4>
                <p class="product-category">
                    @foreach($product->categories as $category)
                        <span>{{ $category->category }}</span>
                    @endforeach
                </p>
            </div>

            <div class="condition-wrapper">
                <h4 class="product-sub-title">商品の状態</h4>
                <p class="product-condition">{{ $product->condition->condition }}</p>
            </div>
        </div>

        <div class="product-inner">
            <h3 class="comment-title">コメント({{ $product->comments->count() }})</h3>
            @foreach ($product->comments as $comment)
                <div class="comment-wrapper">
                    <div class="comment-user">
                        <div class="comment-user-img">
                            @if(!empty($comment->user->img_url))
                                <img src="{{ asset('storage/' . $comment->user->img_url) }}" alt="">
                            @else
                                <div class="user-placeholder"></div>
                            @endif
                        </div>
                        <h4 class="comment-user-name">{{ $comment->user->name }}</h4>
                    </div>
                    <p class="comment-content">{{ $comment->comment }}</p>
                </div>
            @endforeach

            <h3 class="product-comment-title">商品へのコメント</h3>
            <div class="comment-box">
                <form action="{{ route('comment.store',$product->id) }}" method="post">
                    @csrf
                    <textarea name="content"></textarea>
                    @error('content')
                        <p class="content-error">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="comment-btn">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection