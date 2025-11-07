<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'pay_method' => $this->faker->randomElement([1, 2]), // ← 数値に変更
            'stripe_payment_intent_id' => 'pi_' . $this->faker->regexify('[A-Za-z0-9]{14}'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
