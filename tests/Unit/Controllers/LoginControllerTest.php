<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_login_view()
    {
        $response = $this->get('/users/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('account.login');
        $response->assertViewHas('title', 'Đăng nhập');
    }

    public function test_register_returns_signup_view()
    {
        $response = $this->get('/users/signup');
        
        $response->assertStatus(200);
        $response->assertViewIs('account.signup');
        $response->assertViewHas('title', 'Đăng ký');
    }

    public function test_store_authenticates_valid_user()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        $response = $this->post('/users/login/store', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => false
        ]);

        $response->assertRedirect(route('home'));
        $this->assertTrue(Auth::check());
    }

    public function test_store_redirects_admin_to_admin_page()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/users/login/store', [
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect(route('index'));
    }

    public function test_store_fails_with_invalid_credentials()
    {
        $response = $this->post('/users/login/store', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Account not found or password is incorrect');
    }

    public function test_postregister_creates_new_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->post('/users/signup/store', $userData);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Register successfully');
    }

    public function test_postregister_validates_required_fields()
    {
        $response = $this->post('/users/signup/store', []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'password_confirmation']);
    }

    public function test_logout_logs_out_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/users/logout');

        $response->assertRedirect(route('home'));
        $this->assertFalse(Auth::check());
    }
} 