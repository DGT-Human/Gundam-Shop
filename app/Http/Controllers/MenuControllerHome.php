<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;

class MenuControllerHome extends Controller
{
    protected $menuService;
    protected $productService;
    public function __construct(MenuService $menuService, ProductService $productService)
    {
        $this->menuService = $menuService;
        $this->productService = $productService;
    }
    public function index(Request $request, $id, $parent_id, $slug = '')
    {
        if($parent_id == 0){
            $menu = $this->menuService->find($id);
            $products = $this->productService->show($request, $id);
        }
        if($parent_id != 0){
            $menu = $this->menuService->find($id);
            $products = $this->menuService->getProducts($menu, $request);
        }
        return view('menu', [
            'title' => $menu->name,
            'menu' => $menu,
            'products' => $products
        ]);
    }
}
