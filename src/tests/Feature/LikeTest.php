<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\Test\ConditionSeederTest;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // 条件データを作成しておく
        $this->seed(ConditionSeederTest::class);
    }

    /** @test */
    public function いいねが登録される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
             ->post("/item/{$product->id}/like");

        $this->assertDatabaseHas('mylists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /** @test */
    public function いいねアイコンの色が変化する()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
             ->post("/item/{$product->id}/like");

        $response = $this->get("/item/{$product->id}");
        $response->assertSee('liked'); // bladeで class="liked" 等を想定
    }

    /** @test */
    public function いいねを再度押すと解除される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $user->mylists()->attach($product->id);

        $this->actingAs($user)
             ->post("/item/{$product->id}/like");

        $this->assertDatabaseMissing('mylists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
