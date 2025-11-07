<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //テスト用
        User::create([
            'name' => 'ユーザー1',
            'email' => 'user1@test.com',
            'email_verified_at' => '2025-10-16 22:52:10',
            'password' => Hash::make('password123'),
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル1F',
        ]);

        User::create([
            'name' => 'ユーザー2',
            'email' => 'user2@test.com',
            'email_verified_at' => '2025-10-16 22:52:10',
            'password' => Hash::make('password123'),
            'postcode' => '234-5678',
            'address' => '東京都新宿区',
            'building' => '新宿ビル2F',
        ]);

        User::create([
            'name' => 'test',
            'email' => 'test@test',
            'email_verified_at' => '2025-10-16 22:52:10',
            'password' => Hash::make('testtest'),
            'postcode' => '444-4444',
            'address' => 'テスト県',
            'building' => ' テストビル1F',
        ]);
    }
}
