<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use App\Http\Services\Product\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;

class WishlistControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;
    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo mock cho ProductService
        $this->productService = Mockery::mock(ProductService::class);
        $this->app->instance(ProductService::class, $this->productService);
        
        // Tạo user test
        $this->user = User::factory()->create();
        
        // Tạo product test
        $this->product = Product::factory()->create();
    }

    public function test_index_displays_wishlist()
    {
        // Arrange
        $this->actingAs($this->user);
        
        // Tạo wishlist item
        Wishlist::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id
        ]);

        $this->productService->shouldReceive('getAll')
            ->once()
            ->andReturn(collect([$this->product]));

        // Act
        $response = $this->get('users/wishlist');

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('wishlist');
        $response->assertViewHas('wishlists');
        $response->assertViewHas('productCarts');
    }

    public function test_store_adds_product_to_wishlist()
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->post("users/wishlist/add/{$this->product->id}");

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id
        ]);
    }

    public function test_destroy_removes_product_from_wishlist()
    {
        // Arrange
        $this->actingAs($this->user);
        
        // Tạo wishlist item
        Wishlist::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id
        ]);

        // Act
        $response = $this->delete("users/wishlist/remove/{$this->product->id}");

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect();
        
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id
        ]);
    }

    public function test_store_prevents_duplicate_wishlist_items()
    {
        // Arrange
        $this->actingAs($this->user);
        
        // Tạo wishlist item lần đầu
        $this->post("users/wishlist/add/{$this->product->id}");
        
        // Act - thử tạo lại item đã tồn tại
        $response = $this->post("users/wishlist/add/{$this->product->id}");

        // Assert
        $this->assertDatabaseCount('wishlists', 1);
        $response->assertStatus(302);
        $response->assertRedirect();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 