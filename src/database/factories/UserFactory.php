<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // 共通パスワードに固定（テストしやすい）
            'postcode' => $this->faker->postcode(),
            'address' => $this->faker->city() . $this->faker->streetAddress(),
            'building' => $this->faker->optional()->word() . 'ビル',
            'email_verified_at' => now(),
            'remember_token' => \Str::random(10),
        ];
    }

    /**
     * 未認証状態のユーザーを作る時（任意）
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}