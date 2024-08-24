<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Menu\CreateFormRequest;
use App\Models\Menu;
use App\Http\services\Menu\MenuService;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService){
        $this->menuService = $menuService;
    }

    public function create(){
        return view('admin.menu.add',[
            'title' => 'Thêm danh mục mới',
            'menus' => $this->menuService->getParent()
        ]);
    }

    public function store(CreateFormRequest $request){
        $result = $this->menuService->create($request);

        if($result == true){
            return redirect()->back()->with('success', 'Dữ liệu đã được thêm thành công');
        }
            return redirect()->back();

    }

    public function index(){
        return view('admin.menu.list',['title' => 'Danh sách danh mục mới nhất', 'menus' => $this->menuService->getAll(0)]);
    }

    public function show(Menu $menu){
        return view('admin.menu.edit',[
            'title' => 'Chỉnh sửa danh mục',
            $menu -> name,
            'menu' => $menu,
            'menus' => $this->menuService->getParent()
        ]);
    }

    public function update(CreateFormRequest $request, Menu $menu){
        $result = $this->menuService->update($request, $menu);
        if($result == true){
            return redirect()->back()->with('success', 'Dữ liệu đã được cập nhật thành công');
        }
            return redirect()->back();
    }

    public function destroy(Request $request){
        $result = $this -> menuService -> destroy($request);
        if($result) {
            return response()->json(['error' => false, 'message' => 'Xóa thành công']);
        }
            return response()->json(['error' => true, 'message' => 'Xóa thất bại']);
    }
}
