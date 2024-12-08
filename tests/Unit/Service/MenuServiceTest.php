<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Models\Menu;
use App\Http\services\Menu\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MenuServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_menu_success()
    {
        // Giả lập request đầu vào
        $request = \Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('name')->andReturn('Menu 1');
        $request->shouldReceive('input')->with('parent_id')->andReturn(0);
        $request->shouldReceive('input')->with('description')->andReturn('Description of menu');
        $request->shouldReceive('input')->with('content')->andReturn('Content of menu');
        $request->shouldReceive('input')->with('active')->andReturn(1);
        $request->shouldReceive('input')->with('thumb')->andReturn('thumb.jpg');

        $menuService = new MenuService();

        // Thực hiện tạo mới menu
        $result = $menuService->create($request);

        // Kiểm tra xem có trả về menu mới không
        $this->assertInstanceOf(Menu::class, $result);
        $this->assertEquals('Menu 1', $result->name);
    }

    public function test_create_menu_failure()
    {
        // Giả lập request đầu vào
        $request = \Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->andReturn(null);

        $menuService = new MenuService();

        // Thực hiện tạo menu (sẽ lỗi vì thiếu thông tin)
        $result = $menuService->create($request);

        // Kiểm tra kết quả trả về
        $this->assertFalse($result);
        $this->assertEquals('Thêm menu thất bại', session('error'));
    }

    public function test_show_menus()
    {
        // Tạo dữ liệu mẫu trong cơ sở dữ liệu
        Menu::factory()->create(['parent_id' => 1, 'name' => 'Test Menu']);

        $menuService = new MenuService();

        // Lấy danh sách menu có parent_id = 1
        $menus = $menuService->show();

        // Kiểm tra xem có ít nhất một menu được trả về
        $this->assertNotEmpty($menus);
        $this->assertEquals('Test Menu', $menus->first()->name);
    }

    public function test_get_parent_menus()
    {
        // Tạo dữ liệu mẫu trong cơ sở dữ liệu
        Menu::factory()->create(['parent_id' => 0, 'name' => 'Parent Menu']);

        $menuService = new MenuService();

        // Lấy danh sách menu có parent_id = 0
        $parentMenus = $menuService->getParent();

        // Kiểm tra xem có ít nhất một menu cha
        $this->assertNotEmpty($parentMenus);
        $this->assertEquals('Parent Menu', $parentMenus->first()->name);
    }

    public function test_destroy_menu_success()
    {
        // Tạo dữ liệu mẫu trong cơ sở dữ liệu
        $menu = Menu::factory()->create([
            'parent_id' => 0,
            'name' => 'Test Menu',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'active' => 1,
            'slug' => 'test-menu',
            'thumb' => 'test-thumb.jpg',
        ]);


        $request = \Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('id')->andReturn($menu->id);

        $menuService = new MenuService();

        // Xóa menu
        $result = $menuService->destroy($request);

        // Kiểm tra kết quả xóa thành công
        $this->assertNull(Menu::find($menu->id));
    }

    public function test_destroy_menu_failure()
    {
        $request = \Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('id')->andReturn(999);

        $menuService = new MenuService();

        // Thực hiện xóa menu không tồn tại
        $result = $menuService->destroy($request);

        // Kiểm tra kết quả xóa thất bại
        $this->assertFalse($result);
    }

    public function test_update_menu_success()
    {
        // Tạo dữ liệu mẫu trong cơ sở dữ liệu
        $menu = Menu::factory()->create(['parent_id' => 0, 'name' => 'Old Menu']);

        $request = \Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('name')->andReturn('Updated Menu');
        $request->shouldReceive('input')->with('parent_id')->andReturn(0);
        $request->shouldReceive('input')->with('description')->andReturn('Updated Description');
        $request->shouldReceive('input')->with('content')->andReturn('Updated Content');
        $request->shouldReceive('input')->with('active')->andReturn(1);
        $request->shouldReceive('input')->with('thumb')->andReturn('updated_thumb.jpg');

        $menuService = new MenuService();

        // Thực hiện cập nhật menu
        $result = $menuService->update($request, $menu);

        // Kiểm tra menu đã được cập nhật
        $this->assertTrue($result);
        $menu->refresh();
        $this->assertEquals('Updated Menu', $menu->name);
    }

    public function test_update_menu_failure()
    {
        $request = \Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->andReturn(null);

        $menuService = new MenuService();

        // Thực hiện cập nhật menu với dữ liệu không hợp lệ
        $result = $menuService->update($request, new Menu());

        // Kiểm tra kết quả cập nhật thất bại
        $this->assertFalse($result);
        $this->assertEquals('Cập nhật menu thất bại', session('error'));
    }
}
