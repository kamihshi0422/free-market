<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\Test\ConditionSeederTest;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(ConditionSeederTest::class);
    }

    /** @test */
    public function ログイン済みユーザーはコメントできる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post("/item/{$product->id}/comment", [
            'content' => 'テストコメント',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'テストコメント',
        ]);
    }

    /** @test */
    public function ログイン前のユーザーはコメントできない()
    {
        $product = Product::factory()->create();

        $response = $this->post("/item/{$product->id}/comment", [
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function コメントが255文字以上の場合バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $longComment = str_repeat('あ', 256);
        $response = $this->actingAs($user)->post("/item/{$product->id}/comment", [
            'content' => $longComment,
        ]);

        $response->assertSessionHasErrors('content');
    }
}