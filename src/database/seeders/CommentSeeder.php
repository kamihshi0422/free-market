<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::create([
            'user_id' => 1,
            'product_id' => 1,
            'comment' => 'とても良い商品でした！また購入したいです。',
        ]);

        Comment::create([
            'user_id' => 2,
            'product_id' => 1,
            'comment' => '少し傷がありましたが、概ね満足です。',
        ]);
    }
}