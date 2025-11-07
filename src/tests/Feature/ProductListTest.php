<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Condition;

class ProductListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        // マイグレーション＆必要シード投入
        $this->artisan('migrate:fresh');
        $this->seed(\Database\Seeders\Test\ConditionSeederTest::class);
    }

    /** @test */
    public function 全商品を取得できる()
    {
        // condition_idを指定して商品を作成（外部キーエラー防止）
        $products = Product::factory()
            ->count(3)
            ->create(['condition_id' => Condition::inRandomOrder()->first()->id]);

        $response = $this->get('/');
        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertSee($product->name);
    }
    }

    /** @test */
    public function 購入済み商品は_sold_と表示される()
    {
        $purchase = \App\Models\Purchase::factory()->create();
        $product = $purchase->product;

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('sold');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();
        $conditionId = Condition::inRandomOrder()->first()->id;

        $myProduct = Product::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $conditionId,
        ]);

        $otherProduct = Product::factory()->create([
            'condition_id' => $conditionId,
        ]);

        $this->actingAs($user);
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee($myProduct->name);
        $response->assertSee($otherProduct->name);
    }
}
