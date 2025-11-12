<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class PaymentSelectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Test\ConditionSeederTest::class);
    }

    /** @test */
    public function 小計画面で選択した支払い方法が反映される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
                        ->get(route('purchase.show', $product->id) . '?pay_method=2');

        $response->assertStatus(200);
        $response->assertSee('カード払い');
    }
}