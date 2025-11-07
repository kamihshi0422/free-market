<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Database\Seeders\Test\CategorySeederTest;
use Database\Seeders\Test\ConditionSeederTest;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // 条件とカテゴリデータをシード
        $this->seed(ConditionSeederTest::class);
        $this->seed(CategorySeederTest::class);
    }

    /** @test */
    public function 必要な情報が表示される()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'brand_name' => 'Test Brand',
            'price' => 1000,
            'detail' => 'Test description',
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
        $product = Product::factory()->create();

        // シード済みカテゴリから2件ランダムに取得して紐付け
        $categories = Category::inRandomOrder()->limit(2)->get();
        $product->categories()->attach($categories->pluck('id'));

        $response = $this->get("/item/{$product->id}");

        foreach ($categories as $category) {
            $response->assertSee($category->category); // カラム名は 'category'
        }
    }
}
