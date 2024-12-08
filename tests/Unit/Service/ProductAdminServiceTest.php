<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Services\Product\ProductAdminService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductAdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = new ProductAdminService();
    }

    /** @test */
    public function it_can_get_active_menus()
    {
        // Arrange
        Menu::factory()->create(['active' => 1]);
        Menu::factory()->create(['active' => 0]);

        // Act
        $result = $this->productService->getMenu();

        // Assert
        $this->assertEquals(1, $result->count());
        $this->assertEquals(1, $result->first()->active);
    }

    /** @test */
    public function it_validates_price_correctly()
    {
        // Arrange
        $request = new Request();
        $request->merge([
            'price' => 100,
            'price_sale' => 50
        ]);

        // Act
        $result = $this->invokeMethod($this->productService, 'isValidPrice', [$request]);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function it_fails_validation_when_sale_price_higher_than_original()
    {
        // Arrange
        $request = new Request();
        $request->merge([
            'price' => 100,
            'price_sale' => 150
        ]);

        // Act
        $result = $this->invokeMethod($this->productService, 'isValidPrice', [$request]);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_insert_product()
    {
        // Arrange
        $menu = Menu::factory()->create();
        
        $request = new Request();
        $request->merge([
            'name' => 'Test Product',
            'description' => 'Test description',
            'content' => 'Test content',
            'menu_id' => $menu->id,
            'price' => 10000,
            'price_sale' => 5000,
            'active' => 1,
            'quantity' => 100,
            'thumb' => 'test.jpg'
        ]);

        // Act
        $result = $this->productService->insert($request);

        // Assert
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'Test description',
            'content' => 'Test content',
            'menu_id' => $menu->id,
            'price' => 10000,
            'price_sale' => 5000,
            'active' => 1,
            'quantity' => 100,
            'thumb' => 'test.jpg'
        ]);
    }

    /** @test */
    public function it_can_delete_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $request = new Request();
        $request->merge(['id' => $product->id]);

        // Act
        $result = $this->productService->delete($request);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Helper method để gọi private/protected methods
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
} 