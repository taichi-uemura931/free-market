<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前が未入力の場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'username' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_メールアドレスが未入力の場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_パスワードが未入力の場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_パスワードが8文字未満の場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_パスワード確認と一致しない場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_全て正しく入力した場合_会員登録成功しログイン画面に遷移する()
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/email/verify');
    }
}
