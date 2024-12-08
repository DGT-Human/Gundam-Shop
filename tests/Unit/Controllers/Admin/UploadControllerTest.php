<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Services\UploadService;
use Illuminate\Http\Request;
use Mockery;

class UploadControllerTest extends TestCase
{
    protected $uploadService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo mock cho UploadService
        $this->uploadService = Mockery::mock(UploadService::class);
        $this->controller = new UploadController($this->uploadService);
    }

    public function test_store_returns_success_response_when_upload_successful()
    {
        // Arrange
        $request = new Request();
        $expectedUrl = 'path/to/uploaded/file.jpg';
        
        // Mock phương thức store của UploadService
        $this->uploadService
            ->shouldReceive('store')
            ->once()
            ->with($request)
            ->andReturn($expectedUrl);

        // Act
        $response = $this->controller->store($request);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'error' => false,
            'url' => $expectedUrl
        ], json_decode($response->getContent(), true));
    }

    public function test_store_returns_error_response_when_upload_fails()
    {
        // Arrange
        $request = new Request();
        
        // Mock phương thức store trả về false khi upload thất bại
        $this->uploadService
            ->shouldReceive('store')
            ->once()
            ->with($request)
            ->andReturn(false);

        // Act
        $response = $this->controller->store($request);

        // Assert
        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'error' => true
        ], json_decode($response->getContent(), true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 