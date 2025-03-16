<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchasePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_購入画面が表示される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'seller_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->get("/purchase/{$product->product_id}");

        $response->assertStatus(200);
        $response->assertSee('商品購入');
    }
}
