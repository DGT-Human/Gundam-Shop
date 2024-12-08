<?php

namespace Service;

use App\Http\Services\User\AccountService;
use App\Models\Cart;
use App\Models\Customers;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $accountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountService = new AccountService();
    }

    public function testUpdateUserSuccess()
    {
        // Tạo user test
        $user = User::factory()->create();

        // Tạo request giả lập
        $request = new Request([
            'name' => 'New Name',
            'email' => 'new@email.com',
            'phone' => '0123456789',
            'address' => 'New Address',
            'city' => 'New City'
        ]);

        // Thực hiện update
        $result = $this->accountService->update($request, $user->id);

        // Kiểm tra kết quả
        $this->assertTrue($result);
        $this->assertEquals('New Name', $user->fresh()->name);
        $this->assertEquals('new@email.com', $user->fresh()->email);
        $this->assertEquals('0123456789', $user->fresh()->phone);
        $this->assertEquals('New Address', $user->fresh()->address);
        $this->assertEquals('New City', $user->fresh()->city);
    }

    public function testUpdateUserWithInvalidId()
    {
        $request = new Request([
            'name' => 'New Name'
        ]);

        $result = $this->accountService->update($request, 999);
        $this->assertFalse($result);
    }

    public function testGetOrdersWithValidUser()
    {
        // Tạo user và customer
        $user = User::factory()->create();
        $customer = Customers::factory()->create([
            'email' => $user->email
        ]);

        // Tạo một số cart items
        Cart::factory()->count(3)->create([
            'customer_id' => $customer->id,
            'created_at' => now()
        ]);

        $orders = $this->accountService->getOrders($user->id);

        $this->assertNotNull($orders);
        $this->assertEquals(1, $orders->count()); // Vì các cart được tạo cùng thời điểm nên sẽ được group lại
    }

    public function testGetOrdersWithInvalidUser()
    {
        $orders = $this->accountService->getOrders(999);
        $this->assertEmpty($orders);
    }

    public function testChangePasswordSuccess()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword')
        ]);

        $request = new Request([
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'confirm_password' => 'newpassword'
        ]);

        $result = $this->accountService->changePassword($request, $user->id);

        $this->assertTrue($result);
        $this->assertTrue(\Hash::check('newpassword', $user->fresh()->password));
    }

    public function testChangePasswordWithWrongOldPassword()
    {
        // Tạo user với mật khẩu đã hash
        $user = User::factory()->create();
        
        // Set mật khẩu một cách rõ ràng
        $user->password = Hash::make('oldpassword');
        $user->save();

        // Kiểm tra mật khẩu ban đầu được set đúng
        $this->assertTrue(
            Hash::check('oldpassword', $user->password),
            'Initial password should be set correctly'
        );

        // Tạo request với mật khẩu cũ sai
        $request = new Request([
            'current_password' => 'wrongpassword',  // Mật khẩu cũ sai
            'new_password' => 'newpassword',
            'confirm_password' => 'newpassword'
        ]);

        // Thực hiện đổi mật khẩu
        $result = $this->accountService->changePassword($request, $user->id);
        
        // Kiểm tra kết quả
        $this->assertFalse($result);
        
        // Lấy user mới từ database
        $updatedUser = User::find($user->id);
        
        // Kiểm tra mật khẩu cũ vẫn hoạt động
        $this->assertTrue(
            Hash::check('oldpassword', $updatedUser->password),
            'Old password should still work'
        );
    }

    public function testCancelOrder()
    {
        // Tạo product với số lượng ban đầu
        $product = Product::factory()->create([
            'quantity' => 10
        ]);

        // Tạo customer và cart
        $customer = Customers::factory()->create();
        $cart = Cart::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'created_at' => now()
        ]);

        $this->accountService->cancel($cart->id, $cart->created_at);

        // Kiểm tra status của cart
        $this->assertEquals('canceled', $cart->fresh()->status);
        
        // Kiểm tra số lượng product đã được hoàn lại
        $this->assertEquals(12, $product->fresh()->quantity);
    }
} 