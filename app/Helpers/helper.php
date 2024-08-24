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

    public static function menu ($menus, $parent_id = 0){
        $html = '';
        foreach ($menus as $key => $menu){
            if ($menu->parent_id == $parent_id){
                $html .= '<li><a href="/danh-muc/'. $menu->id .'-'. str::slug($menu->name, '-') .'.html">   ' . $menu->name . '</a>';
                if(self::isChild($menus, $menu->id)){  //Nếu self::isChild($menus, $menu->id) trả về true, kết quả cuối cùng sẽ là một danh sách HTML <ul> chứa các mục con (sub-menu) của menu hiện tại.
                                                        //Nếu self::isChild($menus, $menu->id)  trả về false, phần mã trong khối if sẽ không được thực thi, nghĩa là menu hiện tại không có menu con.
                    $html .= '<ul class="sub-menu">';
                    $html .= self::menu($menus, $menu->id); //Đệ Quy:
                    $html .= '</ul>';
                }

                $html .= '</li>';
            }
        }
        return $html;
    }

    public static function isChild($menus, $id){
        foreach ($menus as $menu){
            if($menu->parent_id == $id){
                return true;
            }
        }
        return false;
    }

    public static function price($price = 0, $price_sale = 0){
        if($price_sale != 0){
            return number_format($price_sale).'đ';
        }
        if($price != 0){
            return number_format($price).'đ';
        }
        return '<a href="/lien-he.html">Liên hệ</a>';
    }
}
