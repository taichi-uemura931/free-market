<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_検索キーワードで商品が表示される()
    {
        Product::factory()->create(['product_name' => 'Nintendo Switch']);
        Product::factory()->create(['product_name' => 'PlayStation']);

        $response = $this->get('/search?query=Switch');

        $response->assertSee('Nintendo Switch');
        $response->assertDontSee('PlayStation');
    }

    public function test_検索状態がマイリストページでも保持されている()
    {
        $user = \App\Models\User::factory()->create();

        $product = \App\Models\Product::factory()->create([
            'product_name' => 'Switch',
        ]);

        $user->favorites()->create([
            'product_id' => $product->product_id,
        ]);

        $response = $this->actingAs($user)->get('/products/mylist?query=Switch');

        $response->assertStatus(200);
        $response->assertSee('Switch');
    }
}
