<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Services\Order\OrderService;
use Mockery;
use Illuminate\View\View;

class OrderControllerTest extends TestCase
{
    protected $orderService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = Mockery::mock(OrderService::class);
        $this->controller = new OrderController($this->orderService);
    }

    public function test_index_returns_correct_view()
    {
        // Arrange
        $orders = [
            ['id' => 1, 'customer' => 'Test Customer']
        ];
        $this->orderService->shouldReceive('getAll')->once()->andReturn($orders);

        // Act
        $response = $this->controller->index();

        // Assert
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('admin.order.list', $response->getName());
        $this->assertEquals('Danh sách đơn hàng', $response->getData()['title']);
        $this->assertEquals($orders, $response->getData()['orders']);
    }

    public function test_show_returns_correct_view()
    {
        // Arrange
        $id = 1;
        $date = '2024-03-20';
        $order = ['id' => 1, 'status' => 'pending'];
        $products = [
            ['id' => 1, 'name' => 'Product 1']
        ];

        $this->orderService->shouldReceive('getOrder')->once()
            ->with($id, $date)->andReturn($order);
        $this->orderService->shouldReceive('getProducts')->once()
            ->with($id, $date)->andReturn($products);

        // Act
        $response = $this->controller->show($id, $date);

        // Assert
        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('admin.order.detail', $response->getName());
        $this->assertEquals('Chi tiết đơn hàng', $response->getData()['title']);
        $this->assertEquals($order, $response->getData()['order']);
        $this->assertEquals($products, $response->getData()['products']);
    }

    public function test_shipping_calls_service_and_redirects()
    {
        // Arrange
        $id = 1;
        $date = '2024-03-20';
        $this->orderService->shouldReceive('submit')->once()
            ->with($id, $date)->andReturn(true);

        // Act
        $response = $this->controller->shipping($id, $date);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    public function test_cancel_calls_service_and_redirects()
    {
        // Arrange
        $id = 1;
        $date = '2024-03-20';
        $this->orderService->shouldReceive('cancel')->once()
            ->with($id, $date)->andReturn(true);

        // Act
        $response = $this->controller->cancel($id, $date);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    public function test_complete_calls_service_and_redirects()
    {
        // Arrange
        $id = 1;
        $date = '2024-03-20';
        $this->orderService->shouldReceive('complete')->once()
            ->with($id, $date)->andReturn(true);

        // Act
        $response = $this->controller->complete($id, $date);

        // Assert
        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}