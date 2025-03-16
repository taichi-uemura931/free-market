<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_住所変更画面が表示される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'seller_id' => $user->user_id,
        ]);

        $response = $this->actingAs($user)->get('/address/edit?product_id=' . $product->product_id);

        $response->assertStatus(200);
        $response->assertSee('住所の変更');
    }
}
