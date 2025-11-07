<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Purchase;
use Mockery;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Condition が必要なので最低1件作成
        Condition::factory()->create(['id' => 1]);
    }

    protected function tearDown(): void
    {
        Mockery::close(); // モックをクリーンアップ
        parent::tearDown();
    }

    /** @test */
    public function 配送先変更後に購入画面に住所が反映される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['condition_id' => 1]);

        $this->actingAs($user)
             ->post("/purchase/address/{$product->id}", [
                 'postcode' => '123-4567',
                 'address' => 'Tokyo, Shibuya',
             ]);

        $response = $this->get("/purchase/{$product->id}");
        $response->assertSee('123-4567');
        $response->assertSee('Tokyo, Shibuya');
    }

    /** @test */
    public function 購入時に送付先住所が紐づく()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['condition_id' => 1]);

        // Stripe モック（静的メソッドを置き換え）
        $mock = Mockery::mock('overload:Stripe\Checkout\Session');
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'sess_123',
                'url' => '/fake-stripe-url',
            ]);

        // 住所変更
        $this->actingAs($user)
            ->post(route('address.update', $product->id), [
                'postcode' => '123-4567',
                'address' => 'Tokyo, Shibuya',
            ]);

        // 仮購入（Stripe ページにリダイレクトされるが、DB に登録される）
        $this->actingAs($user)
            ->post(route('purchases.store', $product->id), [
                'pay_method' => 2, // カード払い
                'address' => '123-4567 Tokyo, Shibuya',
            ]);

        // DB に仮購入が作成されているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'address' => '123-4567 Tokyo, Shibuya',
        ]);
    }
}