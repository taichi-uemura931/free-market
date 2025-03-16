<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductsCategoriesSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        $categoryIds = Category::pluck('category_id')->all();

        foreach ($products as $product) {
            $randomCategories = collect($categoryIds)->random(rand(1, min(3, count($categoryIds))))->toArray();
            $product->categories()->attach($randomCategories);
        }
    }
}
