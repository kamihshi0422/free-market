<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Condition;
use Mockery;
use Stripe\Checkout\Session as StripeSession;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Condition::factory()->create(['condition' => '良好']);
    }

    /** @test */
    public function 購入ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $mock = Mockery::mock('alias:Stripe\Checkout\Session');
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'sess_123',
                'url' => '/fake-stripe-url'
            ]);

        $this->actingAs($user);

        $response = $this->post("/purchase/{$product->id}", [
            'pay_method' => 2,
            'address' => $user->postcode . ' ' . $user->address,
        ]);

        $response->assertRedirect('/fake-stripe-url');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面でsoldと表示される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    /** @test */
    public function プロフィールの購入商品一覧に追加される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertSee($product->name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
