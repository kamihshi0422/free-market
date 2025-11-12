<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が表示される()
    {
        $condition = Condition::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Test Product',
            'brand_name' => 'Test Brand',
            'price' => 1000,
            'detail' => 'Test description',
            'condition_id' => $condition->id,
        ]);

        $response = $this->get("/item/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->brand_name);
        $response->assertSee(number_format($product->price));
        $response->assertSee($product->detail);
    }

    /** @test */
    public function 複数選択されたカテゴリが表示されている()
    {
        $categories = Category::factory()->count(2)->create();

        $product = Product::factory()->create();

        $product->categories()->attach($categories->pluck('id'));

        $response = $this->get("/item/{$product->id}");

        foreach ($categories as $category) {
            $response->assertSee($category->category);
        }
    }
}
