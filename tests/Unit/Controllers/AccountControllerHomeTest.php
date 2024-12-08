<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\AccountControllerHome;
use App\Http\services\user\AccountService;
use App\Http\services\Product\ProductService;
use Illuminate\Http\Request;
use Mockery;

class AccountControllerHomeTest extends TestCase
{
    protected $accountService;
    protected $productService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo mock cho các service
        $this->accountService = Mockery::mock(AccountService::class);
        $this->productService = Mockery::mock(ProductService::class);
        
        // Khởi tạo controller với các service mock
        $this->controller = new AccountControllerHome(
            $this->accountService,
            $this->productService
        );
    }

    public function test_index_returns_correct_view()
    {
        // Arrange
        $id = '1';
        $orders = ['order1', 'order2'];
        $products = ['product1', 'product2'];
        
        $this->accountService->shouldReceive('getOrders')
            ->with($id)
            ->once()
            ->andReturn($orders);
            
        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn($products);

        // Act
        $response = $this->controller->index($id);

        // Assert
        $this->assertEquals('account.myaccount', $response->getName());
        $this->assertEquals('Thông tin đơn hàng', $response->getData()['title']);
        $this->assertEquals($orders, $response->getData()['groups']);
        $this->assertEquals($products, $response->getData()['productCarts']);
    }

    public function test_setting_returns_correct_view()
    {
        // Arrange
        $products = ['product1', 'product2'];
        
        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn($products);

        // Act
        $response = $this->controller->setting();

        // Assert
        $this->assertEquals('account.setting', $response->getName());
        $this->assertEquals('Cài đặt tài khoản', $response->getData()['title']);
        $this->assertEquals($products, $response->getData()['productCarts']);
    }

    public function test_update_redirects_back()
    {
        // Arrange
        $request = new Request();
        $id = '1';
        
        $this->accountService->shouldReceive('update')
            ->with($request, $id)
            ->once();

        // Act
        $response = $this->controller->update($request, $id);

        // Assert
        $this->assertTrue($response->isRedirect());
    }

    public function test_order_returns_correct_view_for_single_order()
    {
        // Arrange
        $id = '1';
        $date = '2024-03-20';
        $order = [
            [
                'customer_id' => '123',
                'order_details' => 'details',
            ]
        ];
        $customer = ['name' => 'Test Customer'];
        $products = ['product1', 'product2'];

        $this->accountService->shouldReceive('getOrder')
            ->with($id, $date)
            ->once()
            ->andReturn($order);

        $this->accountService->shouldReceive('getCustomer')
            ->with('123')
            ->once()
            ->andReturn($customer);

        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn($products);

        // Act
        $response = $this->controller->order($id, $date);

        // Assert
        $this->assertEquals('account.orderDetail', $response->getName());
        $this->assertEquals('Đơn hàng của tôi', $response->getData()['title']);
        $this->assertEquals($customer, $response->getData()['customer']);
        $this->assertEquals($products, $response->getData()['productCarts']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 