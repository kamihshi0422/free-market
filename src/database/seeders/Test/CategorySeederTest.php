<?php

namespace Database\Seeders\Test;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeederTest extends Seeder
{
    public function run()
    {
        Category::create(['category' => '時計']);
        Category::create(['category' => '家電']);
        Category::create(['category' => 'ファッション']);
        Category::create(['category' => 'パソコン']);
        Category::create(['category' => '雑貨']);
    }
}
