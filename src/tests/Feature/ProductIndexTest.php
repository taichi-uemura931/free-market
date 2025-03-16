<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    public function test_商品一覧ページが表示される()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('商品一覧');
    }
}
