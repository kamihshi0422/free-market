<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductSellTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品出品情報が保存できる()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $condition = Condition::factory()->create();

        // GD 不要のダミーファイル
        $file = UploadedFile::fake()->create('product.jpg', 100); // 100KB の空ファイル

        $this->actingAs($user)
             ->post('/sell', [
                 'name' => 'New Product',
                 'brand_name' => 'Brand',
                 'detail' => 'Description',
                 'condition_id' => $condition->id,
                 'price' => 1000,
                 'img_url' => $file,
                 'category_ids' => [$category->id],
             ]);

        $this->assertDatabaseHas('products', [
            'name' => 'New Product',
            'brand_name' => 'Brand',
            'price' => 1000,
        ]);

        Storage::disk('public')->assertExists('products_images/' . $file->hashName());
    }
}
