<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねした商品だけが表示される()
    {
        $user = User::factory()->create();
        $liked = Product::factory()->create(['product_name' => 'LIKE_ITEM']);
        $unliked = Product::factory()->create(['product_name' => 'UNLIKE_ITEM']);

        $user->favorites()->create(['product_id' => $liked->product_id]);

        $response = $this->actingAs($user)->get('/products/mylist');

        $response->assertSee('LIKE_ITEM');
        $response->assertDontSee('UNLIKE_ITEM');
    }

    public function test_購入済み商品はSOLDと表示される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'sold']);

        Order::factory()->create([
            'product_id' => $product->product_id,
            'buyer_id' => $user->user_id,
        ]);

        $user->favorites()->create(['product_id' => $product->product_id]);

        $response = $this->actingAs($user)->get('/products/mylist');
        $response->assertSee('SOLD');
    }

    public function test_未認証ユーザーはマイリストが表示されない()
    {
        $response = $this->get('/products/mylist');
        $response->assertRedirect('/login');
    }
}
