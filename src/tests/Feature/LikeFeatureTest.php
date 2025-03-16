<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいね追加と解除が機能する()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/favorite/' . $product->product_id);
        $this->assertDatabaseHas('favorites', ['user_id' => $user->user_id, 'product_id' => $product->product_id]);

        $this->actingAs($user)->post('/favorite/' . $product->product_id);
        $this->assertDatabaseMissing('favorites', ['user_id' => $user->user_id, 'product_id' => $product->product_id]);
    }
}