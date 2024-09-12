<?php

namespace App\Helpers;

use Illuminate\Support\str;

class helper{
    public static function menus($menus, $parent_id = 0, $char = ''){
        $html = '';
        foreach ($menus as $key => $menu){
            if ($menu->parent_id == $parent_id){
                $html .= '<tr>
                            <th>' . $menu->id . '</th>
                            <th>' . $char . $menu->name . '</th>
                            <th>' . self ::active($menu->active). '</th>
                            <th>' . $menu->updated_at . '</th>
                            <th> &nbsp; </th>
                            <th>
                                 <a href="/admin/menus/edit/'. $menu->id .'" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                 <a href="#" onclick="removeRow('. $menu-> id.', \'/admin/menus/destroy\')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </th>
                        ';
                        unset($menus[$key]);
                $html .= self::menus($menus, $menu->id, $char.'|--');
                $html .= '</tr>';
            }
        }
        return $html;
    }

    public static function active ($active = 0){
        if($active == 1){
            return '<span class="badge bg-success">Active</span>';
        }
        return '<span class="badge bg-danger">Inactive</span>';
    }

    public static function menu($menus, $parent_id = 0) {
        // Chuyển Collection thành mảng
        $menusArray = $menus->toArray();

        // Sắp xếp mảng $menusArray theo id tăng dần
        usort($menusArray, function($a, $b) {
            return $a['id'] - $b['id']; // Hoặc $b['id'] - $a['id'] nếu muốn sắp xếp giảm dần
        });

        $html = '';
        foreach ($menusArray as $menu) {
            if ($menu['parent_id'] == $parent_id) {
                $html .= '<li><a href="/danh-muc/' . $menu['id'] . '-' . $menu['parent_id'] . '-' . str::slug($menu['name'], '-') . '.html"> ' . $menu['name'] . '</a>';
                if (self::isChild($menusArray, $menu['id'])) {
                    $html .= '<ul class="sub-menu">';
                    $html .= self::menu(collect($menusArray), $menu['id']); // Đệ quy, cần chuyển lại thành Collection nếu cần
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
        }
        return $html;
    }

    public static function menuMobile($menus, $parent_id = 0) {
        // Chuyển Collection thành mảng
        $menusArray = $menus->toArray();

        // Sắp xếp mảng $menusArray theo id tăng dần
        usort($menusArray, function($a, $b) {
            return $a['id'] - $b['id']; // Hoặc $b['id'] - $a['id'] nếu muốn sắp xếp giảm dần
        });

        $html = '';
        foreach ($menusArray as $menu) {
            if ($menu['parent_id'] == $parent_id) {
                $html .= '<li><a href="/danh-muc/' . $menu['id'] . '-' . $menu['parent_id'] . '-' . str::slug($menu['name'], '-') . '.html"> ' . $menu['name'] . '</a>';
                if (self::isChild($menusArray, $menu['id'])) {
                    $html .= '<ul class="sub-menu-m">';
                    $html .= self::menuMobile(collect($menusArray), $menu['id']); // Đệ quy, cần chuyển lại thành Collection nếu cần
                    $html .= '</ul>';
                    $html .='<span class="arrow-main-menu-m">
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </span>';
                    $html .= '</li>';
                }
            }
        }
        return $html;
    }

    public static function isChild($menus, $id) {
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $id) {
                return true;
            }
        }
        return false;
    }

    public static function price($price = 0, $price_sale = 0){
        if($price_sale != 0){
            return 'Giá gốc: <del style="color: red;">' . number_format($price) . 'đ</del><br>' .
                'Đang Sale: <span style="color: green;">' . number_format($price_sale) . 'đ</span>';
        }
        if($price != 0){
            return number_format($price).'đ';
        }
        return '<a href="https://www.facebook.com/profile.php?id=100010954298010&locale=vi_VN">Liên hệ</a>';
    }

}
