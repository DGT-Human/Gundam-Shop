<?php

namespace Tests\Unit\Helper;

use PHPUnit\Framework\TestCase;
use App\Helpers\helper;

class HelperTest extends TestCase
{
    public function test_active_method_returns_active_html()
    {
        $result = helper::active(1);
        $this->assertEquals('<span class="badge bg-success">Active</span>', $result);
    }

    public function test_active_method_returns_inactive_html()
    {
        $result = helper::active(0);
        $this->assertEquals('<span class="badge bg-danger">Inactive</span>', $result);
    }
    public function test_price_method_with_sale_price()
    {
        $result = helper::price(100000, 80000);
        $expected = 'Giá gốc: <del style="color: red;">100,000đ</del><br>Đang Sale: <span style="color: green;">80,000đ</span>';
        $this->assertEquals($expected, $result);
    }

    public function test_price_method_without_sale_price()
    {
        $result = helper::price(100000);
        $this->assertEquals('100,000đ', $result);
    }

    public function test_price_method_without_price()
    {
        $result = helper::price(0, 0);
        $this->assertEquals('<a href="https://www.facebook.com/profile.php?id=100010954298010&locale=vi_VN">Liên hệ</a>', $result);
    }
    public function test_is_child_method_returns_true()
    {
        $menus = [
            ['id' => 1, 'parent_id' => 0],
            ['id' => 2, 'parent_id' => 1],
        ];
        $result = helper::isChild($menus, 1);
        $this->assertTrue($result);
    }

    public function test_is_child_method_returns_false()
    {
        $menus = [
            ['id' => 1, 'parent_id' => 0],
            ['id' => 2, 'parent_id' => 1],
        ];
        $result = helper::isChild($menus, 3);
        $this->assertFalse($result);
    }
    public function test_menus_method()
    {
        $menus = collect([
            (object)['id' => 1, 'name' => 'Menu 1', 'parent_id' => 0, 'active' => 1, 'updated_at' => '2024-11-15'],
            (object)['id' => 2, 'name' => 'Menu 2', 'parent_id' => 1, 'active' => 0, 'updated_at' => '2024-11-15']
        ]);

        $result = helper::menus($menus);

        $this->assertStringContainsString('Menu 1', $result);
        $this->assertStringContainsString('Menu 2', $result);
        $this->assertStringContainsString('<span class="badge bg-success">Active</span>', $result);
        $this->assertStringContainsString('<span class="badge bg-danger">Inactive</span>', $result);
    }
    public function test_menu_method()
    {
        $menus = collect([
            ['id' => 1, 'name' => 'Menu 1', 'parent_id' => 0],
            ['id' => 2, 'name' => 'Menu 2', 'parent_id' => 1]
        ]);

        $result = helper::menu($menus);

        $this->assertStringContainsString('<li><a href="/danh-muc/1-0-menu-1.html"> Menu 1</a>', $result);
        $this->assertStringContainsString('<li><a href="/danh-muc/2-1-menu-2.html"> Menu 2</a>', $result);
    }
}
