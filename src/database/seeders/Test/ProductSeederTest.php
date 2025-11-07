<?php

namespace Database\Seeders\Test;

use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;

class ProductSeederTest extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = User::all();
        $conditions = Condition::all();

        if ($users->count() === 0 || $conditions->count() === 0) {
            $this->command->info('ユーザーまたは状態が存在しません。先に UserSeederTest と ConditionSeederTest を実行してください。');
            return;
        }

        $products = [
            [
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'img_url' => 'products_images/Armani+Mens+Clock.jpg',
                'categories' => [1, 3],
            ],
            [
                'name' => 'ノートPC',
                'brand_name' => '',
                'detail' => '高性能なノートパソコン',
                'price' => 45000,
                'img_url' => 'products_images/Living+Room+Laptop.jpg',
                'categories' => [2, 4],
            ],
        ];

        foreach ($products as $index => $data) {
            $user = $users[$index % $users->count()];
            $condition = $conditions[$index % $conditions->count()];

            $product = Product::create([
                'user_id' => $user->id,
                'condition_id' => $condition->id,
                'name' => $data['name'],
                'brand_name' => $data['brand_name'],
                'detail' => $data['detail'],
                'price' => $data['price'],
                'img_url' => $data['img_url'],
            ]);

            $product->categories()->attach($data['categories']);
        }
    }
}
