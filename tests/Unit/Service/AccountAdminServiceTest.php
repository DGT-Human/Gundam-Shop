<?php

namespace Service;

use App\Http\services\user\AccountAdminService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $accountAdminService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountAdminService = new AccountAdminService();
    }

    public function testGetAllUsers()
    {
        // Tạo một số người dùng mẫu
        User::factory()->user()->count(3)->create();

        // Gọi phương thức getAll
        $users = $this->accountAdminService->getAll();

        // Kiểm tra kết quả
        $this->assertNotNull($users);
        $this->assertCount(3, $users);
    }

    public function testUpdateUser()
    {
        // Tạo một người dùng mẫu
        $user = User::factory()->create();

        $request = new \Illuminate\Http\Request([
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'phone' => '123456789',
            'address' => 'New Address',
            'city' => 'New City',
        ]);

        $result = $this->accountAdminService->update($request, $user->id);

        $this->assertTrue($result);
        $this->assertEquals('New Name', $user->fresh()->name);
        // ... existing code ...
    }

    public function testChangePassword()
    {
        // Tạo một người dùng mẫu
        $user = User::factory()->create(['password' => bcrypt('oldpassword')]);

        $request = new \Illuminate\Http\Request([
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $result = $this->accountAdminService->changePassword($request, $user->id);

        $this->assertTrue($result);
        $this->assertTrue(\Hash::check('newpassword', $user->fresh()->password));
        // ... existing code ...
    }
} 