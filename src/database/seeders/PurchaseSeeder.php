<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Purchase::create([
            'user_id' => 1,
            'product_id' => 2,
            'pay_method' => '1',
            'stripe_payment_intent_id' => 'pi_1234567890abcdef',
        ]);
    }
}
