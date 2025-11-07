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

        // ユーザー1が商品2をいいね
        $user1->mylists()->attach(2);

        // ユーザー2が商品1, 商品2をいいね
        $user2->mylists()->attach([1,2]);
    }
}
