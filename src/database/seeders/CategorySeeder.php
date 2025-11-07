<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['category' => 'ファッション']);
        Category::create(['category' => '家電']);
        Category::create(['category' => 'インテリア']);
        Category::create(['category' => 'レディース']);
        Category::create(['category' => 'メンズ']);
        Category::create(['category' => 'コスメ']);
        Category::create(['category' => '本']);
        Category::create(['category' => 'ゲーム']);
        Category::create(['category' => 'スポーツ']);
        Category::create(['category' => 'キッチン']);
        Category::create(['category' => 'ハンドメイド']);
        Category::create(['category' => 'アクセサリー']);
        Category::create(['category' => 'おもちゃ']);
        Category::create(['category' => 'ベビー・キッズ']);
    }
}
