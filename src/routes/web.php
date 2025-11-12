<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verificationEmail'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->name('verification.send');
});

Route::get('/',[ProductController::class,'showTop'])->name('top.show');
Route::get('/item/{id}', [ProductController::class, 'showDetail'])->name('detail.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{id}/like', [ProductController::class, 'like'])->name('products.like');
    Route::post('/item/{id}/comment', [ProductController::class, 'storeComment'])->name('comment.store');
    Route::get('/purchase/{id}', [ProductController::class, 'showPurchase'])->name('purchase.show');
    Route::post('/purchase/{id}', [ProductController::class, 'purchase'])->name('purchase.store');
    Route::get('/purchase/address/{id}', [ProductController::class, 'editAddress'])->name('address.edit');
    Route::post('/purchase/address/{id}', [ProductController::class, 'updateAddress'])->name('address.update');
    Route::get('/sell', [ProductController::class, 'showExhibition'])->name('exhibition.show');
    Route::post('/sell', [ProductController::class, 'exhibition'])->name('exhibition.store');

    Route::get('/mypage', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
});