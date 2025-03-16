<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_プロフィール更新画面が表示される()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/mypage/edit');

        $response->assertStatus(200);
        $response->assertSee('プロフィール設定');
    }
}
