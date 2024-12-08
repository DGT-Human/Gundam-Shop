<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Models\Slider;
use App\Http\Services\Slider\SliderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\UploadedFile;

class SliderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $sliderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sliderService = new SliderService();
        Storage::fake('public');
    }

    public function test_insert_slider_successfully()
    {
        $request = new \Illuminate\Http\Request([
            'name' => 'Test Slider',
            'url' => 'http://example.com',
            'thumb' => UploadedFile::fake()->image('slider.jpg'),
            'sort_by' => 1,
            'active' => 1,
        ]);

        $result = $this->sliderService->insert($request);
        $this->assertTrue($result);
        $this->assertDatabaseHas('sliders', [
            'name' => 'Test Slider',
            'url' => 'http://example.com',
        ]);
    }

    public function test_insert_slider_fails()
    {
        $request = new Request(); // Empty request to cause failure

        $result = $this->sliderService->insert($request);

        $this->assertFalse($result);
        $this->assertTrue(Session::has('error'));
    }

    public function test_show_returns_correct_data()
    {
        // Create test sliders
        Slider::factory()->count(3)->create();

        $result = $this->sliderService->show();

        $this->assertEquals(3, $result->count());
        $this->assertArrayHasKey('name', $result->first());
        $this->assertArrayHasKey('thumb', $result->first());
        $this->assertArrayHasKey('url', $result->first());
    }

    public function test_get_returns_paginated_data()
    {
        Slider::factory()->count(20)->create();

        $result = $this->sliderService->get();

        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(20, $result->total());
    }

    public function test_update_slider_successfully()
    {
        $slider = Slider::factory()->create();
        $request = new Request([
            'name' => 'Updated Slider',
            'url' => 'http://updated.com'
        ]);

        $this->sliderService->update($request, $slider);

        $this->assertDatabaseHas('sliders', [
            'id' => $slider->id,
            'name' => 'Updated Slider',
            'url' => 'http://updated.com'
        ]);
        
        $this->assertTrue(Session::has('success'));
    }

    public function test_delete_slider_successfully()
    {
        $slider = Slider::factory()->create([
            'thumb' => 'storage/sliders/test.jpg'
        ]);

        Storage::put('public/sliders/test.jpg', 'fake image content');

        $request = new Request(['id' => $slider->id]);

        $result = $this->sliderService->delete($request);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('sliders', ['id' => $slider->id]);
        Storage::disk('public')->assertMissing('sliders/test.jpg');
    }

    public function test_delete_slider_with_invalid_id()
    {
        $request = new Request(['id' => 999]);

        $result = $this->sliderService->delete($request);

        $this->assertFalse($result);
    }
} 