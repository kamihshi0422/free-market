<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Test\ConditionSeederTest::class);
    }

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        Product::factory()->create(['name' => 'Apple iPhone']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);

        $response = $this->get('/?keyword=Apple');

        $response->assertSee('Apple iPhone');
        $response->assertDontSee('Samsung Galaxy');
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Apple iPhone']);

        $user->mylists()->attach($product->id);

        $this->actingAs($user);

        $response = $this->get('/?keyword=Apple');
        $response->assertSee('Apple iPhone');

        $response = $this->get('/?tab=mylist&keyword=Apple');
        $response->assertSee('Apple iPhone');
    }
}