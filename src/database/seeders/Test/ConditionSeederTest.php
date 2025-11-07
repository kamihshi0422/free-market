<?php

namespace Database\Seeders\Test;

use App\Models\Condition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeederTest extends Seeder
{
    public function run()
    {
        Condition::create(['condition' => '良好']);
        Condition::create(['condition' => '目立った傷や汚れなし']);
        Condition::create(['condition' => 'やや傷や汚れあり']);
        Condition::create(['condition' => '状態が悪い']);
    }
}
