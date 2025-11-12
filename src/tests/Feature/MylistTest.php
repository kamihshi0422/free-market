<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Test\ConditionSeederTest::class);
    }

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::factory()->create();
        $listedProduct = Product::factory()->create();
        $otherProduct = Product::factory()->create();

        $user->mylists()->attach($listedProduct->id);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($listedProduct->name);
        $response->assertDontSee($otherProduct->name);
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->mylists()->attach($product->id);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $product = Product::factory()->create();

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSee($product->name);
        $response->assertDontSee('Sold');
    }
}
