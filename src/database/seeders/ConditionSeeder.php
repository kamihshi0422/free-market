<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Condition;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Condition::create(['condition' => '良好']);
        Condition::create(['condition' => '目立った傷や汚れなし']);
        Condition::create(['condition' => 'やや傷や汚れあり']);
        Condition::create(['condition' => '状態が悪い']);
    }
}