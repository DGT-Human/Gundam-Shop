<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\MenuControllerHome;
use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;
use Illuminate\Http\Request;
use Mockery;

class MenuControllerHomeTest extends TestCase
{
    protected $menuService;
    protected $productService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo mock cho các service
        $this->menuService = Mockery::mock(MenuService::class);
        $this->productService = Mockery::mock(ProductService::class);
        
        // Khởi tạo controller với các service mock
        $this->controller = new MenuControllerHome(
            $this->menuService,
            $this->productService
        );
    }

    public function test_index_with_parent_id_zero()
    {
        // Arrange
        $request = new Request();
        $id = 1;
        $parent_id = 0;
        $slug = 'test-slug';

        $menu = (object)['id' => 1, 'name' => 'Test Menu'];
        $products = collect(['product1', 'product2']);
        $productCarts = collect(['cart1', 'cart2']);

        // Set up expectations
        $this->menuService->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($menu);

        $this->productService->shouldReceive('show')
            ->with($request, $id)
            ->once()
            ->andReturn($products);

        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn($productCarts);

        // Act
        $response = $this->controller->index($request, $id, $parent_id, $slug);

        // Assert
        $this->assertEquals('Test Menu', $response->getData()['title']);
        $this->assertEquals($menu, $response->getData()['menu']);
        $this->assertEquals($products, $response->getData()['products']);
        $this->assertEquals($productCarts, $response->getData()['productCarts']);
    }

    public function test_index_with_non_zero_parent_id()
    {
        // Arrange
        $request = new Request();
        $id = 1;
        $parent_id = 2;
        $slug = 'test-slug';

        $menu = (object)['id' => 1, 'name' => 'Test Menu'];
        $products = collect(['product1', 'product2']);
        $productCarts = collect(['cart1', 'cart2']);

        // Set up expectations
        $this->menuService->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($menu);

        $this->menuService->shouldReceive('getProducts')
            ->with($menu, $request)
            ->once()
            ->andReturn($products);

        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn($productCarts);

        // Act
        $response = $this->controller->index($request, $id, $parent_id, $slug);

        // Assert
        $this->assertEquals('Test Menu', $response->getData()['title']);
        $this->assertEquals($menu, $response->getData()['menu']);
        $this->assertEquals($products, $response->getData()['products']);
        $this->assertEquals($productCarts, $response->getData()['productCarts']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 