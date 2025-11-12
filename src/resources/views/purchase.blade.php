@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="buy-wrapper">
    {{-- 左側：商品情報・支払い方法・配送先 --}}
    <div class="payment-box">
        <div class="product-info">
            <img class="product-img" src="{{ asset('storage/' . $product->img_url) }}" alt="{{ $product->name }}">
            <div class="product-details">
                <h3 class="product-name">{{ $product->name }}</h3>
                <p class="product-price">{{ number_format($product->price) }}</p>
            </div>
        </div>

        {{-- 支払い方法選択 --}}
        <div class="payment-section">
            <h3 class="payment-title">支払い方法</h3>
            <form action="{{ route('purchase.show', $product->id) }}" method="get">
                @csrf
                <select name="pay_method" onchange="this.form.submit()">
                    <option value="" disabled hidden {{ empty($pay_method) ? 'selected' : '' }}>選択してください</option>
                    <option value="1" {{ $pay_method == 1 ? 'selected' : '' }}>
                        {{ $pay_method == 1 ? '✓ コンビニ払い' : 'コンビニ払い' }}
                    </option>
                    <option value="2" {{ $pay_method == 2 ? 'selected' : '' }}>
                        {{ $pay_method == 2 ? '✓ カード払い' : 'カード払い' }}
                    </option>
                </select>
            </form>
        </div>

        {{-- 配送先 --}}
        <div class="address-section">
            <div class="address-section-header">
                <h3 class="address-title">配送先</h3>
                <a class="btn-change" href="{{ route('address.edit', $product->id) }}">変更する</a>
            </div>
            <p class="address-view">〒 {{ $address->postcode }}</p>
            <p class="address-view">{{ $address->address }} {{ $address->building }}</p>
        </div>
    </div>

    {{-- 右側：購入確認 --}}
    <div class="buy-box">
        <form id="buyForm" action="{{ route('purchase.store', $product->id) }}" method="post" target="_blank">
            @csrf
            <input type="hidden" name="pay_method" value="{{ $pay_method ?? 1 }}">
            <input type="hidden" name="address" value="{{ $address->postcode }} {{ $address->address }} {{ $address->building }}">

            <div class="confirm-section">
                <table class="confirm-table">
                    <tr>
                        <th>商品代金</th>
                        <td class="confirm-price">{{ number_format($product->price) }}</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        <td>
                            @if($pay_method == 1)
                                コンビニ払い
                            @elseif($pay_method == 2)
                                カード払い
                            @else
                                コンビニ払い
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <button type="submit" class="btn-buy">購入する</button>
        </form>
    </div>
</div>

<script>
document.getElementById('buyForm').addEventListener('submit', function() {
    setTimeout(() => {
        window.location.href = "{{ route('top.show') }}";
    }, 500);
});
</script>
@endsection