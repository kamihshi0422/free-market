<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product1 = Product::create([
            'user_id' => 1,
            'condition_id' => 1,
            'name' => '腕時計',
            'brand_name' => 'Rolax',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'img_url' => 'products_images/Armani+Mens+Clock.jpg',
        ]);
        $product1->categories()->attach([1, 4, 11]);

        $product2 = Product::create([
            'user_id' => 2,
            'condition_id' => 2,
            'name' => 'HDD',
            'brand_name' => '西芝',
            'detail' => '高速で信頼性の高いハードディスク',
            'price' => 5000,
            'img_url' => 'products_images/HDD+Hard+Disk.jpg',
        ]);
        $product2->categories()->attach([2]);

        $product = Product::create([
            'user_id' => 3,
            'condition_id' => 3,
            'name' => '玉ねぎ3束',
            'brand_name' => 'なし',
            'detail' => '新鮮な玉ねぎ3束のセット',
            'price' => 300,
            'img_url' => 'products_images/iLoveIMG+d.jpg',
        ]);
        $product->categories()->attach([9]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 4,
            'name' => '革靴',
            'brand_name' => '',
            'detail' => 'クラシックなデザインの革靴',
            'price' => 4000,
            'img_url' => 'products_images/Leather+Shoes+Product+Photo.jpg',
        ]);
        $product->categories()->attach([1,4]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'ノートPC',
            'brand_name' => '',
            'detail' => '高性能なノートパソコン',
            'price' => 45000,
            'img_url' => 'products_images/Living+Room+Laptop.jpg',
        ]);
        $product->categories()->attach([2]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 2,
            'name' => 'マイク',
            'brand_name' => 'なし',
            'detail' => '高音質のレコーディング用マイク',
            'price' => 8000,
            'img_url' => 'products_images/Music+Mic+4632231.jpg',
        ]);
        $product->categories()->attach([2]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 3,
            'name' => 'ショルダーバッグ',
            'brand_name' => '',
            'detail' => 'おしゃれなショルダーバッグ',
            'price' => 3500,
            'img_url' => 'products_images/Purse+fashion+pocket.jpg',
        ]);
        $product->categories()->attach([1,3]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 4,
            'name' => 'タンブラー',
            'brand_name' => 'なし',
            'detail' => '使いやすいタンブラー',
            'price' => 500,
            'img_url' => 'products_images/Tumbler+souvenir.jpg',
        ]);
        $product->categories()->attach([9]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'コーヒーミル',
            'brand_name' => 'Starbacks',
            'detail' => '手動のコーヒーミル',
            'price' => 4000,
            'img_url' => 'products_images/Waitress+with+Coffee+Grinder.jpg',
        ]);
        $product->categories()->attach([9]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 2,
            'name' => 'メイクセット',
            'brand_name' => '',
            'detail' => '便利なメイクアップセット',
            'price' => 2500,
            'img_url' => 'products_images/外出メイクアップセット.jpg',
        ]);
        $product->categories()->attach([5]);
    }
}