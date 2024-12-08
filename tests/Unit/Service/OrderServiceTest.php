<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Http\Services\Order\OrderService;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Customers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function test_get_all_orders()
    {
        // Arrange
        $customer = \Database\Factories\CustomerFactory::new()->create();
        $product = Product::factory()->create();
        Cart::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100000
        ]);

        // Act
        $result = $this->orderService->getAll();

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($customer->name, $result->first()->customer_name);
        $this->assertEquals(200000, $result->first()->total);
    }

    public function test_get_order_details()
    {
        // Arrange
        $customer = \Database\Factories\CustomerFactory::new()->create();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100000,
            'created_at' => now()
        ]);

        // Act
        $result = $this->orderService->getOrder($customer->id, $cart->created_at);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($customer->id, $result['customer_id']);
        $this->assertEquals(100000, $result['total']);
    }

    public function test_submit_order()
    {
        // Arrange
        $customer = \Database\Factories\CustomerFactory::new()->create();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'status' => 'pending'
        ]);

        // Act
        $this->orderService->submit($customer->id, $cart->created_at);

        // Assert
        $this->assertEquals('shipping', Cart::first()->status);
    }

    public function test_cancel_order()
    {
        // Arrange
        $customer = \Database\Factories\CustomerFactory::new()->create();
        $product = Product::factory()->create(['quantity' => 10]);
        $cart = Cart::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'status' => 'pending'
        ]);

        // Act
        $this->orderService->cancel($customer->id, $cart->created_at);

        // Assert
        $this->assertEquals('canceled', Cart::first()->status);
        $this->assertEquals(12, Product::first()->quantity); // 10 + 2
    }

    public function test_complete_order()
    {
        // Arrange
        $customer = \Database\Factories\CustomerFactory::new()->create();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'status' => 'shipping'
        ]);

        // Act
        $this->orderService->complete($customer->id, $cart->created_at);

        // Assert
        $this->assertEquals('completed', Cart::first()->status);
    }
} 