<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Menu;
use App\Http\Services\Product\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductService();
    }

    public function test_get_products_with_pagination()
    {
        // Arrange
        Product::factory()->count(10)->create();

        // Act
        $result = $this->productService->get(1);

        // Assert
        $this->assertEquals(4, $result->count());
    }

    public function test_get_all_active_products()
    {
        // Arrange
        Product::factory()->count(3)->create(['active' => 1]);
        Product::factory()->count(2)->create(['active' => 0]);

        // Act
        $result = $this->productService->getAll();

        // Assert
        $this->assertEquals(3, $result->count());
    }

    public function test_show_products_by_menu()
    {
        // Arrange
        $menu = Menu::factory()->create();
        $childMenu = Menu::factory()->count(2)->create(['parent_id' => $menu->id]);
        Product::factory()->count(5)->create([
            'menu_id' => $childMenu->first()->id,
            'active' => 1
        ]);

        // Act
        $request = request()->merge(['price' => 'asc']);
        $result = $this->productService->show($request, $menu->id);

        // Assert
        $this->assertEquals(5, $result->total());
    }

    public function test_get_single_product()
    {
        // Arrange
        $product = Product::factory()->create(['active' => 1]);

        // Act
        $result = $this->productService->getProduct($product->id);

        // Assert
        $this->assertEquals($product->id, $result->id);
    }

    public function test_get_more_products()
    {
        // Arrange
        $product = Product::factory()->create();
        Product::factory()->count(5)->create(['active' => 1]);

        // Act
        $result = $this->productService->more($product->id);

        // Assert
        $this->assertEquals(4, $result->count());
        $this->assertNotContains($product->id, $result->pluck('id'));
    }

    public function test_search_products_by_keyword()
    {
        // Arrange
        Product::factory()->create(['name' => 'iPhone 12', 'active' => 1]);
        Product::factory()->create(['name' => 'iPhone 13', 'active' => 1]);
        Product::factory()->create(['name' => 'Samsung S21', 'active' => 1]);

        // Act
        $result = $this->productService->search('iPhone');

        // Assert
        $this->assertEquals(2, $result->total());
    }

    public function test_search_products_by_price_range()
    {
        // Arrange
        Product::factory()->create(['name' => 'iPhone 12', 'price' => 500000, 'active' => 1]);
        Product::factory()->create(['name' => 'iPhone 13', 'price' => 1000000, 'active' => 1]);
        Product::factory()->create(['name' => 'iPhone 14', 'price' => 15000000, 'active' => 1]);

        // Act
        $result = $this->productService->searchMoney(0, 15000000, 1, 1);

        // Assert
        $this->assertEquals(1, $result->total());
    }
} 