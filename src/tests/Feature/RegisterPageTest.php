<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterPageTest extends TestCase
{
    public function test_会員登録画面が表示される()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('会員登録');
    }
}
