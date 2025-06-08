<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentSelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_支払い方法選択が反映される()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->get('/purchase/' . $product->id);
        $response->assertStatus(200)->assertSee('支払い方法');
    }
}