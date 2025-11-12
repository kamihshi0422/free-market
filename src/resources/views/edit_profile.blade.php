@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
@endsection

@section('content')
<div class="form-wrapper">
    <h1 class="form-ttl">プロフィール設定</h1>
    <form action="{{ route('profile.update')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="user-box">
            @if(!empty($user->img_url))
                <img src="{{ asset('storage/' . $user->img_url) }}" alt="プロフィール画像">
            @else
                <div class="user-placeholder"></div>
            @endif
            <label for="img" class="img-btn">画像を選択する</label>
            <input type="file" name="img" id="img" accept="image/*">
            @error('img')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-group-ttl">
                <label for="name" class="form-label">ユーザー名</label>
            </div>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-group-ttl">
                <label for="postcode" class="form-label">郵便番号</label>
            </div>
            <input type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}">
            @error('postcode')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-group-ttl">
                <label for="address" class="form-label">住所</label>
            </div>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-group-ttl">
                <label for="building" class="form-label">建物名</label>
            </div>
            <input type="text" name="building" value="{{ old('building', $user->building) }}">
        </div>

        <button type="submit" class="form-button-submit">更新する</button>
    </form>
</div>
@endsection