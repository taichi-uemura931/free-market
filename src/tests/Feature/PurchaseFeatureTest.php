<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\StripeService;
use Illuminate\Support\Facades\Route;

class PurchaseFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $mockStripeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockStripeService = \Mockery::mock(StripeService::class);

        $this->app->instance(StripeService::class, $this->mockStripeService);
    }

    public function test_カード払い購入処理がモックされる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 2000]);

        $this->mockStripeService
            ->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn((object)['url' => 'https://checkout.stripe.com/mock-card-url']);

        session(['shipping_address' => [
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building_name' => 'サンプルビル',
        ]]);

        $response = $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method' => 'card',
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('mock-card-url', $response->headers->get('Location'));
    }

    public function test_コンビニ払い購入処理がモックされる()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 3000]);

        $this->mockStripeService
            ->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn((object)['url' => 'https://checkout.stripe.com/mock-konbini-url']);

        session(['shipping_address' => [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building_name' => 'テストビル',
        ]]);

        $response = $this->actingAs($user)->post("/purchase/{$product->id}", [
            'payment_method' => 'convenience_store',
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('mock-konbini-url', $response->headers->get('Location'));
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
