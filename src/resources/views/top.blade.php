@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection

@section('content')
<div class="top-box">
    <nav class="top-box__nav">
        <div class="btn-box">
            <a href="{{ route('top.show') }}" class="{{ request()->routeIs('top.show') && request('tab') !== 'mylist' ? 'active' : '' }}">
                おすすめ
            </a>
        </div>

        <div class="btn-box">
            <a href="{{ url('/?tab=mylist' . (request('keyword') ? '&keyword=' . urlencode(request('keyword')) : '')) }}" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </nav>
</div>

<section class="product-list">
    @foreach($products as $product)
    <div class="product-card">
        <a href="{{ route('detail.show', $product->id) }}">
            <img class="product-img" src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->name }}">
        </a>
        <div class="product-detail">
            <div>
                <p>{{ $product->name }}</p>
            </div>
            @if($product->purchase)
                <span class="sold-label">Sold</span>
            @endif
        </div>
    </div>
    @endforeach
</section>
@endsection