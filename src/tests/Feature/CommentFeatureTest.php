<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログインユーザーはコメント投稿できる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/comment/{$product->id}", [
            'comment_text' => 'テストコメント'
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment_text' => 'テストコメント'
        ]);
    }

    public function test_未ログインユーザーはコメント投稿できない()
    {
        $product = Product::factory()->create();

        $response = $this->post("/comment/{$product->id}", [
            'comment_text' => '未ログインコメント'
        ]);

        $response->assertRedirect('/login');
    }

    public function test_コメントが未入力ならバリデーションエラー()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/comment/{$product->id}", [
            'comment_text' => ''
        ]);

        $response->assertSessionHasErrors(['comment_text']);
    }
}
