<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_ログイン画面が表示される()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('ログイン');
    }
}
