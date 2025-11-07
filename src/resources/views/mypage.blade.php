@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-head">
    <div class="user">
        <div class="user-img">
            @if(!empty($user->img_url))
                <img class="" src="{{ asset('storage/' . $user->img_url) }}" alt="">
            @else
                <div class="user-placeholder"></div>
            @endif
        </div>
        <h3 class="user-name"> {{ $user->name }}</h3>
    </div>
    <a class="profile__edit-btn" href="{{ route('profile.edit') }}">プロフィールを編集</a>
</div>

<div class="mypage__nav">
    <a href="{{ route('mypage.show', ['page' => 'sell']) }}" class="{{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('mypage.show', ['page' => 'buy']) }}" class="{{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
</div>

<section class="product-list">
    @if($page === 'sell')
        @foreach($myProducts as $product)
        <div class="product-card">
            <a href="{{ route('detail.show', $product->id) }}">
                <img class="product-img" src="{{ asset('storage/' . $product->img_url) }}" alt="商品画像">
            </a>
            <div class ="product-detail">
                <p >{{ $product->name }}</p>
                @if($product->purchase)
                    <span class="sold-label">Sold</span>
                @endif
            </div>
        </div>
        @endforeach
    @elseif($page === 'buy')
        @foreach($purchases as $purchase)
        <div class="product-card">
            <a href="{{ route('detail.show', $purchase->product->id) }}">
                <img class="product-img" src="{{ asset('storage/' . $purchase->product->img_url) }}" alt="商品画像">
            </a>
            <div class ="product-detail">
                <p>{{ $purchase->product->name }}</p>
            </div>
        </div>
        @endforeach
    @endif
</section>
@endsection