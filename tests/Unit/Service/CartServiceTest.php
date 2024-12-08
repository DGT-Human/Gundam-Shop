<?php

namespace Service;

use App\Http\Services\CartService;
use App\Models\Customers;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
    }

    public function test_create_cart_with_valid_quantity()
    {
        // Tạo sản phẩm mẫu
        $product = Product::factory()->create([
            'quantity' => 10
        ]);

        $request = new Request();
        $request->merge([
            'num-product' => 2,
            'product_id' => $product->id
        ]);

        $result = $this->cartService->create($request);

        $this->assertTrue($result);
        $this->assertEquals(2, Session::get('carts')[$product->id]);
    }

    public function test_create_cart_with_invalid_quantity()
    {
        $product = Product::factory()->create([
            'quantity' => 5
        ]);

        $request = new Request();
        $request->merge([
            'num-product' => 6,
            'product_id' => $product->id
        ]);

        $result = $this->cartService->create($request);

        $this->assertFalse($result);
        $this->assertNull(Session::get('carts'));
    }

    public function test_get_product_from_cart()
    {
        $product = Product::factory()->create([
            'active' => 1
        ]);

        Session::put('carts', [
            $product->id => 2
        ]);

        $result = $this->cartService->getProduct();

        $this->assertCount(1, $result);
        $this->assertEquals($product->id, $result->first()->id);
    }

    public function test_destroy_cart_item()
    {
        $product = Product::factory()->create();
        Session::put('carts', [
            $product->id => 2
        ]);

        $result = $this->cartService->destroy($product->id);

        $this->assertTrue($result);
        $this->assertEmpty(Session::get('carts'));
    }

    public function test_add_carts_successfully()
    {
        // Tạo sản phẩm mẫu với số lượng và trạng thái active
        $product = Product::factory()->create([
            'quantity' => 10,
            'active' => 1,
            'price' => 100000 // Thêm giá sản phẩm
        ]);

        // Thiết lập giỏ hàng trong session
        Session::put('carts', [
            $product->id => 2
        ]);

        // Tạo request với đầy đủ thông tin cần thiết
        $request = new Request();
        $request->merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'content' => 'Test Content'
        ]);

        $result = $this->cartService->addCarts($request);

        // Kiểm tra kết quả
        $this->assertTrue($result);
        
        // Kiểm tra session cart đã được xóa
        $this->assertNull(Session::get('carts'));
        
        // Kiểm tra thông tin khách hàng đã được lưu
        $this->assertDatabaseHas('customers', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address'
        ]);
        
        // Kiểm tra thông tin đơn hàng đã được lưu
        $customer = Customers::where('email', 'test@example.com')->first();
        $this->assertDatabaseHas('carts', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);
    }
} 