@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="form-wrapper">
    <div class="form-heading">
        <h1 class="heading-title">住所の変更</h1>
    </div>
    <form action="{{ route('address.update', $product->id) }}" method="post" >
        @csrf

        <div class="form-group">
            <div class="form-group-title">
                <label class="form-label-item" for="postcode">郵便番号</label>
            </div>
            <input type="text" name="postcode" value="{{ old('postcode', $address->postcode) }}">
            @error('postcode')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-group-title">
                <label class="form-label-item" for="address">住所</label>
            </div>
            <input type="text" name="address" value="{{ old('address', $address->address) }}">
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-group-title">
                <label class="form-label-item" for="building">建物名</label>
            </div>
            <input type="text" name="building" value="{{ old('building', $address->building) }}">
        </div>

        <button type="submit" class="form-button-submit">更新する</button>
    </form>
</div>
@endsection