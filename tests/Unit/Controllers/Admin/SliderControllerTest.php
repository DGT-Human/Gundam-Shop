<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Models\Slider;
use App\Models\User;
use App\Http\Services\Slider\SliderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Illuminate\Support\Facades\Session;

class SliderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $sliderService;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        
        // Mock SliderService
        $this->sliderService = Mockery::mock(SliderService::class);
        $this->app->instance(SliderService::class, $this->sliderService);
        
        // Tạo và đăng nhập user admin
        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    public function test_create_returns_correct_view()
    {
        $this->assertTrue($this->admin->role === 'admin');

        $response = $this->get('/admin/sliders/add');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.sliders.add');
        $response->assertViewHas('title', 'Thêm sliders mới');
    }

    public function test_store_slider_successfully()
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('slider.jpg');
        
        $data = [
            'name' => 'Test Slider',
            'url' => 'http://example.com',
            'sort_by' => 1,
            'active' => 1,
            'thumb' => $file
        ];

        $this->sliderService
            ->shouldReceive('insert')
            ->once()
            ->with(\Mockery::type('App\Http\Requests\Slider\SliderRequest'))
            ->andReturnUsing(function() {
                Session::flash('success', 'Thêm mới thành công');
                return true;
            });

        $response = $this->post('/admin/sliders/add', $data);
        
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Thêm mới thành công');
    }

    public function test_index_displays_sliders()
    {
        $sliders = new \Illuminate\Pagination\LengthAwarePaginator(
            Slider::factory()->count(3)->create(),
            3, // total items
            10, // items per page
            1 // current page
        );
        
        $this->sliderService
            ->shouldReceive('get')
            ->once()
            ->andReturn($sliders);

        $response = $this->get('/admin/sliders/list');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.sliders.list');
        $response->assertViewHas('sliders', $sliders);
        $response->assertViewHas('title', 'Danh sách sliders');
    }

    public function test_show_displays_edit_form()
    {
        $slider = Slider::factory()->create();

        $response = $this->get("/admin/sliders/edit/{$slider->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.sliders.edit');
        $response->assertViewHas('title', 'Cập nhật slider');
        $response->assertViewHas('slider', $slider);
    }

    public function test_update_slider_successfully()
    {
        Storage::fake('public');
        
        $slider = Slider::factory()->create();
        $file = UploadedFile::fake()->image('slider.jpg');
        
        $updateData = [
            'name' => 'Updated Slider',
            'url' => 'http://updated-example.com',
            'sort_by' => 2,
            'active' => 1,
            'thumb' => $file
        ];

        $this->sliderService
            ->shouldReceive('update')
            ->once()
            ->with(\Mockery::type('App\Http\Requests\Slider\SliderRequest'), \Mockery::type('App\Models\Slider'))
            ->andReturnUsing(function() {
                Session::flash('success', 'Cập nhật thành công');
                return true;
            });

        $response = $this->post("/admin/sliders/edit/{$slider->id}", $updateData);
        
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Cập nhật thành công');
    }

    public function test_destroy_slider_successfully()
    {
        $slider = Slider::factory()->create();

        $this->sliderService
            ->shouldReceive('delete')
            ->once()
            ->with(\Mockery::type('Illuminate\Http\Request'))
            ->andReturn(true);

        $response = $this->delete('/admin/sliders/destroy', [
            'id' => $slider->id
        ]);
        
        $response->assertJson([
            'error' => false,
            'message' => 'Xóa thành công'
        ]);
    }

    public function test_destroy_slider_fails()
    {
        $slider = Slider::factory()->create();

        $this->sliderService
            ->shouldReceive('delete')
            ->once()
            ->with(\Mockery::type('Illuminate\Http\Request'))
            ->andReturn(false);

        $response = $this->delete('/admin/sliders/destroy', [
            'id' => $slider->id
        ]);
        
        $response->assertJson([
            'error' => true,
            'message' => 'Xóa thất bại'
        ]);
    }

    public function test_unauthorized_user_cannot_access_sliders()
    {
        auth()->logout();
        
        $response = $this->get('/admin/sliders/list');
        $response->assertRedirect('/users/login');
    }

    public function test_non_admin_user_cannot_access_sliders()
    {
        // Tạo user thường
        $regularUser = User::factory()->user()->create();
        
        // Đăng nhập với user thường
        $this->actingAs($regularUser);

        // Mock SliderService nhưng không cần thiết lập expectation
        $sliderService = $this->mock(SliderService::class);

        // Thực hiện request
        $response = $this->get('/admin/sliders/list')
            ->assertStatus(403)  // Kỳ vọng bị từ chối truy cập
            ->assertForbidden(); // Alias cho assertStatus(403)

        // Đảm bảo service không được gọi
        $sliderService->shouldNotHaveReceived('get');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
} 