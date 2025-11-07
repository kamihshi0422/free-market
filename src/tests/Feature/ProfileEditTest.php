<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 変更項目が初期値として表示される()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'postcode' => '123-4567',
            'address' => 'Tokyo'
        ]);

        $this->actingAs($user);
        $response = $this->get('/mypage/profile');

        $response->assertSee('Test User');
        $response->assertSee('123-4567');
        $response->assertSee('Tokyo');
    }
}