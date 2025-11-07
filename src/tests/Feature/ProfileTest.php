<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール情報が正しく表示される()
    {
        // ユーザー作成
        $user = User::factory()->create(['name' => 'Test User']);

        // 出品商品を作成
        $sellProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'Sell Product',
        ]);

        // 購入商品を作成
        $buyProduct = Product::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $buyProduct->id,
        ]);

        $this->actingAs($user);

        // 出品商品ページ
        $response = $this->get('/mypage?page=sell');
        $response->assertSee('Test User');
        $response->assertSee('Sell Product');
        $response->assertDontSee($buyProduct->name); // 購入商品は見えない

        // 購入商品ページ
        $response = $this->get('/mypage?page=buy');
        $response->assertSee('Test User');
        $response->assertSee($buyProduct->name);
        $response->assertDontSee('Sell Product'); // 出品商品は見えない
    }
}
