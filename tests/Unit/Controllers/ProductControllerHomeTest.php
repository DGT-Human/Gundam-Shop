<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\ProductControllerHome;
use App\Http\services\Product\ProductService;
use App\Http\services\CartService;
use Illuminate\Support\Facades\Session;
use Mockery;

class ProductControllerHomeTest extends TestCase
{
    protected $productService;
    protected $cartService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo mock cho ProductService và CartService
        $this->productService = Mockery::mock(ProductService::class);
        $this->cartService = Mockery::mock(CartService::class);
        
        // Khởi tạo controller với các service đã mock
        $this->controller = new ProductControllerHome(
            $this->productService,
            $this->cartService
        );
    }

    public function test_index_method_returns_correct_view()
    {
        // Arrange
        $productId = 1;
        $slug = 'test-product';
        
        $mockProduct = (object)[
            'id' => $productId,
            'name' => 'Test Product',
            'slug' => $slug
        ];
        
        $mockProductMore = [(object)[
            'id' => 2,
            'name' => 'Related Product'
        ]];
        
        $mockProductCarts = [(object)[
            'id' => 3,
            'name' => 'Cart Product'
        ]];

        // Set up expectations for mock services
        $this->productService->shouldReceive('getProduct')
            ->once()
            ->with($productId)
            ->andReturn($mockProduct);
            
        $this->cartService->shouldReceive('getProduct')
            ->once()
            ->andReturn($mockProductCarts);
            
        $this->productService->shouldReceive('more')
            ->once()
            ->with($productId)
            ->andReturn($mockProductMore);
            
        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn($mockProductCarts);

        Session::shouldReceive('get')
            ->once()
            ->with('carts')
            ->andReturn([]);

        // Act
        $response = $this->controller->index($productId, $slug);

        // Assert
        $this->assertEquals('Test Product', $response->getData()['title']);
        $this->assertEquals($mockProduct, $response->getData()['product']);
        $this->assertEquals([], $response->getData()['cart']);
        $this->assertEquals($mockProductMore, $response->getData()['products']);
        $this->assertEquals($mockProductCarts, $response->getData()['productCarts']);
        $this->assertEquals('product.content', $response->getName());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 