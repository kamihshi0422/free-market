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
        $user = User::factory()->create(['name' => 'Test User']);

        $sellProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => 'Sell Product',
        ]);

        $buyProduct = Product::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $buyProduct->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?page=sell');
        $response->assertSee('Test User');
        $response->assertSee('Sell Product');
        $response->assertDontSee($buyProduct->name);

        $response = $this->get('/mypage?page=buy');
        $response->assertSee('Test User');
        $response->assertSee($buyProduct->name);
        $response->assertDontSee('Sell Product');
    }
}
