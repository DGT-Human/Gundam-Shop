<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\CartController;
use App\Http\services\CartService;
use App\Http\services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery;

class CartControllerTest extends TestCase
{
    protected $cartController;
    protected $cartService;
    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = Mockery::mock(CartService::class);
        $this->productService = Mockery::mock(ProductService::class);
        $this->cartController = new CartController($this->cartService, $this->productService);
    }

    public function test_index_returns_redirect_with_correct_status_code()
    {
        // Arrange
        $request = new Request();
        $this->cartService->shouldReceive('create')->once()->with($request)->andReturn(false);

        // Act
        $response = $this->cartController->index($request);

        // Assert
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(302, $response->getStatusCode()); // Kiểm tra status code redirect
    }

    public function test_show_returns_view_with_success_status()
    {
        // Arrange
        $mockProducts = ['product1', 'product2'];
        $mockAllProducts = ['allProduct1', 'allProduct2'];
        $mockCarts = ['cart1', 'cart2'];

        $this->cartService->shouldReceive('getProduct')->once()->andReturn($mockProducts);
        $this->productService->shouldReceive('getAll')->once()->andReturn($mockAllProducts);
        Session::shouldReceive('get')->with('carts')->once()->andReturn($mockCarts);

        // Act
        $response = $this->cartController->show();

        // Assert
        $this->assertEquals('carts.list', $response->getName());
        $this->assertEquals('Giỏ hàng', $response->getData()['title']);
        $this->assertEquals($mockProducts, $response->getData()['products']);
        $this->assertEquals($mockCarts, $response->getData()['carts']);
        $this->assertEquals($mockAllProducts, $response->getData()['productCarts']);
    }

    public function test_update_redirects_with_correct_status()
    {
        // Arrange
        $request = new Request();
        $this->cartService->shouldReceive('update')
            ->once()
            ->with($request)
            ->andReturn(true);

        // Act
        $response = $this->cartController->update($request);

        // Assert
        $this->assertTrue($response->isRedirect());  // Chỉ kiểm tra có phải redirect
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_remove_redirects_with_correct_status()
    {
        // Arrange
        $id = 1;
        $this->cartService->shouldReceive('destroy')->once()->with($id)->andReturn(true);

        // Act
        $response = $this->cartController->remove($id);

        // Assert
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(302, $response->getStatusCode()); // Kiểm tra status code redirect
    }

    public function test_add_carts_redirects_with_correct_status()
    {
        // Arrange
        $request = new Request();
        $this->cartService->shouldReceive('addCarts')
            ->once()
            ->with($request)
            ->andReturn(true);

        // Act
        $response = $this->cartController->addCarts($request);

        // Assert
        $this->assertTrue($response->isRedirect());  // Chỉ kiểm tra có phải redirect
        $this->assertEquals(302, $response->getStatusCode());
    }

    // Thêm test case cho trường hợp lỗi
    public function test_index_with_service_error()
    {
        // Arrange
        $request = new Request();
        $this->cartService->shouldReceive('create')
            ->once()
            ->with($request)
            ->andThrow(new \Exception('Service error'));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Service error');
        $this->cartController->index($request);
    }

    public function test_show_with_service_error()
    {
        // Arrange
        $this->cartService->shouldReceive('getProduct')
            ->once()
            ->andThrow(new \Exception('Service error'));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Service error');
        $this->cartController->show();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 