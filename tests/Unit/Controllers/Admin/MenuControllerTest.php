<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Models\Menu;
use App\Models\User;
use App\Http\Services\Menu\MenuService;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    // Test xem trang 'create' có hiển thị đúng không
    public function test_create_view_shows_correctly()
    {
        // Giả lập user đã đăng nhập (nếu có xác thực)
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Gửi yêu cầu GET đến route create
        $response = $this->get(route('admin.menu.create'));

        // Kiểm tra mã trạng thái và xem view trả về có chứa 'title'
        $response->assertStatus(200);
        $response->assertViewIs('admin.menu.add');
        $response->assertViewHas('title', 'Thêm danh mục mới');
        $response->assertViewHas('menus');
    }

    // Test hành động store để tạo danh mục
    public function test_store_creates_menu()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Dữ liệu gửi lên để tạo menu
        $data = [
            'name'        => 'Menu Test',
            'parent_id'   => 0,
            'description' => 'Mô tả cho menu test',
            'content'     => 'Nội dung cho menu test',
            'slug'        => 'menu-test',
            'active'      => 1,
            'thumb'       => 'https://via.placeholder.com/150'
        ];

        // Gửi yêu cầu POST tới route store
        $response = $this->post(route('admin.menu.store'), $data);

        // Kiểm tra nếu redirect và thông báo thành công
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Dữ liệu đã được thêm thành công');

        // Kiểm tra xem menu có được tạo trong DB không
        $this->assertDatabaseHas('menus', [
            'name' => 'Menu Test',
        ]);
    }


    // Test hành động index
    public function test_index_view_shows_menus()
    {
        // Giả lập user đã đăng nhập
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Tạo một số menu
        Menu::factory()->create(['name' => 'Menu 1']);
        Menu::factory()->create(['name' => 'Menu 2']);

        // Gửi yêu cầu GET đến route index
        $response = $this->get(route('admin.menu.list'));

        // Kiểm tra xem view trả về có chứa danh sách menu
        $response->assertStatus(200);
        $response->assertViewIs('admin.menu.list');
        $response->assertViewHas('menus');
        $response->assertSee('Menu 1');
        $response->assertSee('Menu 2');
    }

    // Test hành động show (edit)
    public function test_show_view_shows_correct_menu()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Tạo một menu
        $menu = Menu::factory()->create(['name' => 'Menu 1']);

        // Gửi yêu cầu GET tới route show
        $response = $this->get(route('admin.menu.show', ['menu' => $menu->id]));

        // Kiểm tra xem view trả về có chứa tên menu
        $response->assertStatus(200);
        $response->assertViewIs('admin.menu.edit');
        $response->assertViewHas('menu', $menu);
    }

    // Test hành động update
    public function test_update_menu()
    {
        // Giả lập user đã đăng nhập
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Tạo một menu
        $menu = Menu::factory()->create([
            'name' => 'Menu 1',
            'parent_id' => 0,
            'description' => 'Mô tả cũ',
            'content' => 'Nội dung cũ',
            'slug' => 'menu-1',
            'active' => 1,
            'thumb' => 'https://via.placeholder.com/150',
        ]);

        // Dữ liệu gửi lên để cập nhật
        $data = [
            'name' => 'Menu Updated',
            'parent_id' => 0,
            'description' => 'Mô tả mới',
            'content' => 'Nội dung mới',
            'slug' => 'menu-updated',
            'active' => 1,
            'thumb' => 'https://via.placeholder.com/150',
            '_method' => 'post',
        ];

        // Gửi yêu cầu PUT đến route update
        $response = $this->post(route('admin.menu.update', ['menu' => $menu->id]), $data);

        // Kiểm tra nếu redirect và thông báo thành công
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Dữ liệu đã được cập nhật thành công');

        // Kiểm tra xem menu có được cập nhật trong DB không
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'name' => 'Menu Updated',
            'description' => 'Mô tả mới',
            'content' => 'Nội dung mới',
            'slug' => 'menu-updated',
        ]);
    }


    // Test hành động destroy
    public function test_destroy_menu()
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        // Tạo một menu
        $menu = Menu::factory()->create([
            'name' => 'Menu 1',
            'parent_id' => 0,
            'description' => 'Mô tả cũ',
            'content' => 'Nội dung cũ',
            'slug' => 'menu-1',
            'active' => 1,
        ]);
        $this->assertDatabaseHas('menus', ['id' => $menu->id]);

        // Gửi yêu cầu DELETE tới route destroy
        $response = $this->delete(route('admin.menu.destroy', ['id' => $menu->id]));

        // Kiểm tra xem phản hồi có trả về đúng không
        $response->assertStatus(200);
        $response->assertJson(['error' => false, 'message' => 'Xóa thành công']);

        // Kiểm tra xem menu có bị xóa trong DB không
        $this->assertDatabaseMissing('menus', [
            'id' => $menu->id,
        ]);
    }

}
