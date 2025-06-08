<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $primaryKey = 'product_id';
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(1000, 10000),
            'condition' => $this->faker->randomElement([
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い'
            ]),
            'status' => 'available',
            'seller_id' => \App\Models\User::factory(),
        ];
    }
}
