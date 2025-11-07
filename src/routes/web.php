<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhookController;

// 認証系
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showVerifyNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware('throttle:6,1')->name('verification.send');
});

// 商品系
Route::get('/',[ProductController::class,'top'])->name('top.show');
Route::get('/item/{id}', [ProductController::class, 'detail'])->name('detail.show');

Route::middleware(['auth', 'verified'])->group(function () { // ←未ログインは自動で login 画面へ
    Route::post('/item/{id}/like', [ProductController::class, 'like'])->name('products.like');
    Route::post('/item/{id}/comment', [ProductController::class, 'comment'])->name('comment.store');
    Route::get('/purchase/{id}', [ProductController::class, 'showBuy'])->name('purchases.show');
    Route::post('/purchase/{id}', [ProductController::class, 'buy'])->name('purchases.store');

    Route::get('/purchase/address/{id}', [ProductController::class, 'editAddress'])->name('address.edit');
    Route::post('/purchase/address/{id}', [ProductController::class, 'updateAddress'])->name('address.update');

    // 出品機能
    Route::get('/sell', [ProductController::class, 'showSell'])->name('sell.show');
    Route::post('/sell', [ProductController::class, 'sell'])->name('sell.store');

    // マイページ
    Route::get('/mypage', [ProfileController::class, 'myPage'])->name('mypage.show');
    Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
});