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

        // 条件テーブルにダミーデータを作成
        Condition::factory()->create(['condition' => '良好']);
    }

    /** @test */
    public function 購入ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Stripe の静的メソッドをモック
        $mock = Mockery::mock('alias:Stripe\Checkout\Session');
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'sess_123',
                'url' => '/fake-stripe-url'
            ]);

        // ステップ1: ユーザーにログイン
        $this->actingAs($user);

        // ステップ2 & 3: 商品購入画面を開き、購入ボタンを押下
        $response = $this->post("/purchase/{$product->id}", [
            'pay_method' => 2, // カード払い
            'address' => $user->postcode . ' ' . $user->address,
        ]);

        // Stripe のリダイレクト URL が返ってくることを確認
        $response->assertRedirect('/fake-stripe-url');

        // ステップ4: DB に購入レコードが作成されている
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

        // 購入レコードを作成（Stripe 無視）
        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // ステップ1-4: 商品一覧画面を表示
        $response = $this->get('/');

        // Blade 側で「sold」が表示されているか
        $response->assertSee('Sold');
    }

    /** @test */
    public function プロフィールの購入商品一覧に追加される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Stripe 無視で購入レコード作成
        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // ステップ4: プロフィール画面を表示
        $response = $this->actingAs($user)->get('/mypage?page=buy');

        // 購入商品が表示されているか
        $response->assertSee($product->name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
