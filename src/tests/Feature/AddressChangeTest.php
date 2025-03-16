<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_住所変更が反映される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/address/update', [
            'buyer_id' => $user->user_id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building_name' => 'テストビル',
            'product_id' => $product->product_id
        ]);

        $response->assertRedirect();
    }
}