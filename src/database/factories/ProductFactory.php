<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        if (Condition::count() === 0) {
            Condition::factory()->create(['id' => 1]);
        }

        $conditionId = Condition::inRandomOrder()->first()->id;

        return [
            'user_id' => User::factory(),
            'condition_id' => $conditionId,
            'name' => $this->faker->words(2, true),
            'brand_name' => $this->faker->company(),
            'price' => $this->faker->numberBetween(1000, 100000),
            'img_url' => 'products/sample.jpg',
            'detail' => $this->faker->realText(100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
