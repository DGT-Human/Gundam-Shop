<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mockery;

class ForgotPasswordControllerTest extends TestCase
{
    protected $productService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = Mockery::mock(ProductService::class);
        $this->controller = new ForgotPasswordController($this->productService);
    }

    public function test_show_forgot_password_form()
    {
        // Arrange
        $this->productService->shouldReceive('getAll')->once()->andReturn([]);

        // Act
        $response = $this->controller->showForgotPasswordForm();

        // Assert
        $this->assertEquals('account.forgot-password', $response->getName());
        $this->assertEquals('Quên mật khẩu', $response->getData()['title']);
    }

    public function test_handle_forgot_password_with_invalid_email()
    {
        // Arrange
        $request = new Request();
        $request->merge(['email' => 'invalid@email.com']);
        DB::shouldReceive('table')->with('users')->andReturnSelf();
        DB::shouldReceive('where')->with('email', 'invalid@email.com')->andReturnSelf();
        DB::shouldReceive('first')->andReturnNull();

        // Act
        $response = $this->controller->handleForgotPassword($request);

        // Assert
        $this->assertTrue($response->getSession()->has('errors'));
    }

    public function test_handle_forgot_password_with_valid_email()
    {
        // Arrange
        $request = new Request();
        $request->merge(['email' => 'valid@email.com']);
        $user = (object)['email' => 'valid@email.com', 'role' => 'user'];
        
        DB::shouldReceive('table')->with('users')->andReturnSelf();
        DB::shouldReceive('where')->with('email', 'valid@email.com')->andReturnSelf();
        DB::shouldReceive('first')->andReturn($user);

        // Act
        $response = $this->controller->handleForgotPassword($request);

        // Assert
        $this->assertEquals(route('reset.password'), $response->getTargetUrl());
    }

    public function test_show_reset_password_form_without_email()
    {
        // Act
        $response = $this->controller->showResetPasswordForm();

        // Assert
        $this->assertEquals(route('forgot.password'), $response->getTargetUrl());
        $this->assertTrue(session()->has('error'));
    }

    public function test_handle_reset_password_success()
    {
        // Arrange
        $request = new Request();
        $request->merge([
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);
        session(['reset_email' => 'test@email.com']);

        DB::shouldReceive('table')->with('users')->andReturnSelf();
        DB::shouldReceive('where')->with('email', 'test@email.com')->andReturnSelf();
        DB::shouldReceive('update')->andReturn(true);

        // Act
        $response = $this->controller->handleResetPassword($request);

        // Assert
        $this->assertEquals('http://localhost/users/login', $response->getTargetUrl());
        $this->assertTrue(session()->has('success'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 