<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    public function test_マイページが表示される()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('マイページ');
    }
}
