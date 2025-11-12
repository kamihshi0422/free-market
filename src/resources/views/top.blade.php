@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection

@section('content')
<div class="top-nav">
    <nav class="top-nav-menu">
        <div class="top-nav-item">
            <a href="{{ route('top.show') }}" class="{{ request()->routeIs('top.show') && request('tab') !== 'mylist' ? 'active' : '' }}">
                おすすめ
            </a>
        </div>
        <div class="top-nav-item">
            <a href="{{ url('/?tab=mylist' . (request('keyword') ? '&keyword=' . urlencode(request('keyword')) : '')) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </nav>
</div>

<section class="products">
    @foreach($products as $product)
    <div class="product-card">
        <a href="{{ route('detail.show', $product->id) }}">
            <img class="product-card-img" src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->name }}">
        </a>
        <div class="product-card-detail">
            <p class="product-card-name">{{ $product->name }}</p>
            @if($product->purchase)
            <span class="product-card-sold">Sold</span>
            @endif
        </div>
    </div>
    @endforeach
</section>
@endsection
