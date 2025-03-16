<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細ページが表示される()
    {
        $product = Product::factory()->create();
        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }
}
