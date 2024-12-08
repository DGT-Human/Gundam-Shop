<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Http\Services\Product\ProductAdminService;
use App\Http\Requests\Product\ProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = Mockery::mock(ProductAdminService::class);
        $this->app->instance(ProductAdminService::class, $this->productService);

        // Tạo user admin và login
        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    public function test_index_returns_correct_view()
    {
        // Arrange
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            [], // items
            0,  // total
            10, // perPage
            1   // currentPage
        );
        $this->productService->shouldReceive('get')->once()->andReturn($products);
    
        // Act
        $response = $this->get('/admin/products/list');
    
        // Assert
        $response->assertStatus(200)
                ->assertViewIs('admin.products.list')
                ->assertViewHas('title')
                ->assertViewHas('products');
    }

    public function test_create_returns_correct_view()
    {
        // Arrange
        $this->productService->shouldReceive('getMenu')->once()->andReturn([]);

        // Act
        $response = $this->get('/admin/products/add');

        // Assert
        $response->assertStatus(200)
                ->assertViewIs('admin.products.add')
                ->assertViewHas('title')
                ->assertViewHas('menus');
    }

    public function test_store_product()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'menu_id' => 1,
            'price' => 100000,
            'price_sale' => 90000,
            'description' => 'Test description',
            'content' => 'Test content',
            'thumb' => 'test.jpg',
            'active' => 1
        ];
        
        // Mock service để set session trong hàm insert
        $this->productService->shouldReceive('insert')
            ->once()
            ->with(\Mockery::any())  // Sử dụng Mockery::any() thay vì $productData
            ->andReturnUsing(function() {
                session()->flash('success', 'Thêm sản phẩm thành công');
                return true;
            });

        // Act
        $response = $this->post('/admin/products/add', $productData);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Thêm sản phẩm thành công');
    }

    public function test_show_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $this->productService->shouldReceive('getMenu')->once()->andReturn([]);

        // Act
        $response = $this->get("/admin/products/edit/{$product->id}");

        // Assert
        $response->assertStatus(200)
                ->assertViewIs('admin.products.edit')
                ->assertViewHas('title')
                ->assertViewHas('product')
                ->assertViewHas('menus');
    }

    public function test_update_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $updateData = [
            'name' => 'Updated Product',
            'menu_id' => 1,
            'price' => 200000,
            'price_sale' => 180000,
            'description' => 'Updated description',
            'content' => 'Updated content',
            'thumb' => 'updated.jpg',
            'active' => 1
        ];
        
        // Mock service để set session trong hàm update
        $this->productService->shouldReceive('update')
            ->once()
            ->withArgs(function($request, $product) {
                return $request instanceof \App\Http\Requests\Product\ProductRequest 
                    && $product instanceof \App\Models\Product;
            })
            ->andReturnUsing(function($request, $product) {
                session()->flash('success', 'Cập nhật thành công');
                return true;
            });

        // Act
        $response = $this->post("/admin/products/edit/{$product->id}", $updateData);

        // Assert
        $response->assertStatus(302)
                 ->assertSessionHas('success', 'Cập nhật thành công');
    }

    public function test_update_product_fails()
    {
        // Arrange
        $product = Product::factory()->create();
        $updateData = [
            'name' => 'Updated Product',
            'menu_id' => 1,
            'price' => 200000,
            'price_sale' => 180000,
            'description' => 'Updated description',
            'content' => 'Updated content',
            'thumb' => 'updated.jpg',
            'active' => 1
        ];
        
        // Mock service để set session error trong hàm update
        $this->productService->shouldReceive('update')
            ->once()
            ->withArgs(function($request, $product) {
                return $request instanceof \App\Http\Requests\Product\ProductRequest 
                    && $product instanceof \App\Models\Product;
            })
            ->andReturnUsing(function($request, $product) {
                session()->flash('error', 'Cập nhật thất bại');
                return false;
            });

        // Act
        $response = $this->post("/admin/products/edit/{$product->id}", $updateData);

        // Assert
        $response->assertStatus(302)
                 ->assertSessionHas('error', 'Cập nhật thất bại');
    }

    public function test_destroy_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $this->productService->shouldReceive('delete')->once()->andReturn(true);

        // Act
        $response = $this->delete('/admin/products/destroy', [
            'id' => $product->id
        ]);

        // Assert
        $response->assertJson([
            'error' => false,
            'message' => 'Xóa thành công'
        ]);
    }

    public function test_destroy_product_fails()
    {
        // Arrange
        $product = Product::factory()->create();
        $this->productService->shouldReceive('delete')->once()->andReturn(false);

        // Act
        $response = $this->delete('/admin/products/destroy', [
            'id' => $product->id
        ]);

        // Assert
        $response->assertJson([
            'error' => true,
            'message' => 'Xóa thất bại'
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 