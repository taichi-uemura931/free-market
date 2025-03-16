<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('products')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            'user_id' => 1,
            'username' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $products = [
            [
                'product_name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'category' => 'ファッション',
                'condition' => '良好',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'category' => '家電',
                'condition' => '目立った傷や汚れなし',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'category' => 'キッチン',
                'condition' => 'やや傷や汚れあり',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'category' => 'メンズ',
                'condition' => '状態が悪い',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'category' => '家電',
                'condition' => '良好',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'category' => '家電',
                'condition' => '目立った傷や汚れなし',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'category' => 'レディース',
                'condition' => 'やや傷や汚れあり',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'category' => 'アクセサリー',
                'condition' => '状態が悪い',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'category' => 'キッチン',
                'condition' => '良好',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'category' => 'コスメ',
                'condition' => '目立った傷や汚れなし',
                'seller_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $insertData = [];

        foreach ($products as $product) {
            $imageUrl = $product['img_url'];

            $response = Http::get($imageUrl);

            if ($response->successful()) {
                $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);

                $filename = 'products/' . uniqid() . '.' . $extension;

                Storage::disk('public')->put($filename, $response->body());

                $product['img_url'] = $filename;
            } else {

                $product['img_url'] = 'products/default.jpg';
            }

            $product['created_at'] = now();
            $product['updated_at'] = now();
            $insertData[] = $product;
        }

        DB::table('products')->insert($insertData);

    }
}
