<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細ページに情報が表示される()
    {
        $product = Product::factory()->create([
            'product_name' => 'Test Product',
            'brand_name' => 'Test Brand',
            'price' => 1234,
            'description' => 'Test Description',
            'category' => 'ファッション',
            'condition' => '良好',
        ]);

        $category = Category::factory()->create(['name' => 'ファッション']);
        $product->categories()->attach($category->category_id);

        $response = $this->get("/products/{$product->product_id}");

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('Test Brand');
        $response->assertSee('1,234');
        $response->assertSee('Test Description');
        $response->assertSee('ファッション');
        $response->assertSee('良好');
    }
}
