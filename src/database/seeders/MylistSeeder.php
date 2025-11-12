<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class MylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->mylists()->attach(2);
        $user2->mylists()->attach([1,2]);
    }
}