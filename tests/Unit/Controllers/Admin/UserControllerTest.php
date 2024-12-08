<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Http\Services\user\AccountAdminService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $accountAdminService;
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->accountAdminService = Mockery::mock(AccountAdminService::class);
        $this->app->instance(AccountAdminService::class, $this->accountAdminService);

        // Tạo và login với tài khoản admin
        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    public function test_index_returns_correct_view()
    {
        // Arrange
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            collect([
                User::factory()->create([
                    'name' => 'Test User 1',
                    'email' => 'test1@example.com',
                    'phone' => '0123456789',
                    'address' => '123 Test Street',
                ]),
                User::factory()->create([
                    'name' => 'Test User 2',
                    'email' => 'test2@example.com',
                    'phone' => '0987654321',
                    'address' => '456 Test Avenue',
                ])
            ]),
            2, // total items
            10, // items per page
            1 // current page
        );

        $this->accountAdminService->shouldReceive('getAll')->once()->andReturn($users);

        // Act
        $response = $this->get('/admin/accounts/list');

        // Assert
        $response->assertStatus(200)
                ->assertViewIs('admin.user.list')
                ->assertViewHas('title', 'Danh Sách Tài Khoản')
                ->assertViewHas('users', $users);
    }

    public function test_show_returns_correct_view()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->get("/admin/accounts/edit/{$user->id}");

        // Assert
        $response->assertStatus(200)
                ->assertViewIs('admin.user.detail')
                ->assertViewHas('title')
                ->assertViewHas('user');
    }

    public function test_update_user_information()
    {
        // Arrange
        $user = User::factory()->create();
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@email.com',
            'phone' => '0123456789',
            'address' => 'Updated Address'
        ];

        $this->accountAdminService
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        // Act
        $response = $this->post("/admin/accounts/user/{$user->id}/update", $updateData);

        // Assert
        $response->assertRedirect()
                ->assertSessionHas('success', 'Dữ liệu đã được cập nhật thành công');
    }

    public function test_change_password()
    {
        // Arrange
        $user = User::factory()->create();
        $passwordData = [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123'
        ];

        $this->accountAdminService
            ->shouldReceive('changePassword')
            ->once()
            ->andReturn(true);

        // Act
        $response = $this->post("/admin/accounts/user/{$user->id}/change-password", $passwordData);

        // Assert
        $response->assertRedirect()
                ->assertSessionHas('success', 'Đổi mật khẩu thành công');
    }

    public function test_unauthorized_access()
    {
        // Arrange
        auth()->logout();
        $regularUser = User::factory()->user()->create();
        $this->actingAs($regularUser);

        // Act
        $response = $this->get('/admin/accounts/list');

        // Assert
        $response->assertStatus(403);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 