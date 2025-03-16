<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_ユーザー情報取得()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/mypage');
        $response->assertStatus(200)->assertSee($user->username);
    }

    public function test_ユーザー情報変更()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/mypage/update', [
            'username' => '変更後ユーザー名',
            'postal_code' => '111-1111',
            'address' => '東京都渋谷区',
            'building_name' => '新ビル'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['user_id' => $user->user_id, 'username' => '変更後ユーザー名']);
    }
}