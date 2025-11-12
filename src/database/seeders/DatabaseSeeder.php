<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Test\ConditionSeederTest;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('testing')) {
            $this->call(ConditionSeederTest::class);
        } else {
            $this->call([
                UserSeeder::class,
                CategorySeeder::class,
                ConditionSeeder::class,
                ProductSeeder::class,
                MylistSeeder::class,
                PurchaseSeeder::class,
                CommentSeeder::class
            ]);
        }
    }
}