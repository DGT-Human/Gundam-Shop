<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\MainControllerHome;
use App\Http\Services\Slider\SliderService;
use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;
use Illuminate\Http\Request;
use Mockery;

class MainControllerHomeTest extends TestCase
{
    protected $controller;
    protected $sliderService;
    protected $menuService;
    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo mock cho các service
        $this->sliderService = Mockery::mock(SliderService::class);
        $this->menuService = Mockery::mock(MenuService::class);
        $this->productService = Mockery::mock(ProductService::class);

        // Khởi tạo controller với các mock service
        $this->controller = new MainControllerHome(
            $this->sliderService,
            $this->menuService,
            $this->productService
        );
    }

    public function test_index_returns_correct_view()
    {
        // Arrange
        $this->sliderService->shouldReceive('show')->once()->andReturn([]);
        $this->menuService->shouldReceive('show')->once()->andReturn([]);
        $this->productService->shouldReceive('get')->once()->andReturn([]);
        $this->productService->shouldReceive('getAll')->once()->andReturn([]);

        // Act
        $response = $this->controller->index();

        // Assert
        $this->assertEquals('home', $response->getName());
        $this->assertEquals('Gundam Shop', $response->getData()['title']);
    }

    public function test_load_products_with_data()
    {
        // Arrange
        $request = new Request();
        $request->merge(['page' => 1]);
        
        // Tạo mock data với đầy đủ thuộc tính cần thiết
        $mockProducts = [
            (object)[
                'id' => 1,
                'name' => 'Product 1',
                'thumb' => 'images/product1.jpg',
                'price' => 100000,
                'price_sale' => 90000,
            ],
            (object)[
                'id' => 2,
                'name' => 'Product 2',
                'thumb' => 'images/product2.jpg',
                'price' => 200000,
                'price_sale' => 180000,
            ]
        ];

        $this->productService->shouldReceive('get')
            ->with(1)
            ->once()
            ->andReturn($mockProducts);

        // Act
        $response = $this->controller->loadProducts($request);

        // Assert
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('html', json_decode($response->getContent(), true));
    }

    public function test_search_with_results()
    {
        // Arrange
        $request = new Request();
        $request->merge(['search' => 'gundam']);
        
        $this->menuService->shouldReceive('search')
            ->with('gundam')
            ->once()
            ->andReturn(['menu1']);
            
        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn(['cart1']);
            
        $this->productService->shouldReceive('search')
            ->with('gundam')
            ->once()
            ->andReturn(['product1']);

        // Act
        $response = $this->controller->search($request);

        // Assert
        $this->assertEquals('menu', $response->getName());
        $this->assertEquals('Kết quả tìm kiếm', $response->getData()['title']);
    }

    public function test_search_money_with_results()
    {
        // Arrange
        $request = new Request();
        $request->merge([
            'min_price' => 100000,
            'max_price' => 500000,
            'category_id' => 'category/1-2' // Giả lập category_id
        ]);
        
        // Tạo mock cho menu và sản phẩm
        $mockMenus = ['menu1', 'menu2'];
        $mockProducts = [
            (object)[
                'id' => 1,
                'name' => 'Product 1',
                'price' => 150000,
                'price_sale' => 140000,
            ],
            (object)[
                'id' => 2,
                'name' => 'Product 2',
                'price' => 300000,
                'price_sale' => 290000,
            ]
        ];

        $this->menuService->shouldReceive('show')
            ->once()
            ->andReturn($mockMenus);
            
        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn(['cart1']);
            
        $this->productService->shouldReceive('searchMoney')
            ->with(100000, 500000, 1, 2) // Truyền ID và parent_id
            ->once()
            ->andReturn($mockProducts);

        // Act
        $response = $this->controller->searchMoney($request);

        // Assert
        $this->assertEquals('menu', $response->getName());
        $this->assertEquals('Kết quả tìm kiếm', $response->getData()['title']);
        $this->assertEquals($mockProducts, $response->getData()['products']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 