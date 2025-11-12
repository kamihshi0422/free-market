<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class EmailVerifyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 認証メールが送信される()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->post('/email/verification-notification');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function 認証ボタンでメール認証サイトに遷移する()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertStatus(302);
    }

    /** @test */
    public function メール認証完了でプロフィール設定画面に遷移する()
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $response->assertRedirect(route('profile.edit'));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
