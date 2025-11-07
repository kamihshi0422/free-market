<?php

namespace Database\Seeders\Test;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeederTest extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'テストユーザー1',
            'email' => 'user1@test.com',
            'password' => Hash::make('password123'),
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル1F',
        ]);

        User::create([
            'name' => 'テストユーザー2',
            'email' => 'user2@test.com',
            'password' => Hash::make('password123'),
            'postcode' => '234-5678',
            'address' => '東京都新宿区',
            'building' => '新宿ビル2F',
        ]);
    }
}
