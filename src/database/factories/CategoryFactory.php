<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ',
                '本', 'ゲーム', 'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー',
                'おもちゃ', 'ベビー・キッズ',
            ]),
        ];
    }
}
