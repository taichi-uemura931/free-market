<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品出品画面が表示される()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/product/create');

        $response->assertStatus(200);
        $response->assertSee('商品出品');
    }
}
