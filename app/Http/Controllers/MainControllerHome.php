<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Slider\SliderService;
use App\Http\Services\Menu\MenuService;
use App\Http\Services\Product\ProductService;

class MainControllerHome extends Controller
{
    protected $sliderService;
    protected $menuService;

    protected $productService;

    public function __construct(SliderService $sliderService, MenuService $menuService, ProductService $productService)
    {
        $this->sliderService = $sliderService;
        $this->menuService = $menuService;
        $this->productService = $productService;
    }
    public function index()
    {
        return view('home',[
            'title' => 'Gundam Shop',
            'sliders' => $this->sliderService->show(),
            'menus' => $this->menuService->show(),
            'products' => $this->productService->get(),
            'productCarts' => $this->productService->getAll()
        ]);
    }

    public function loadProducts(Request $request)
    {
        $page = $request->input('page', 0);
        $result = $this->productService->get($page);
        if (count($result) != 0) {
            $html = view('product.list', ['products' => $result])->render();
            return response()->json([
                'html' => $html
            ]);
        }
        return response()->json([
            'html' => ''
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');
        $products = $this->productService->search($keyword);
        return view('product.search', ['products' => $products], ['title' => 'Kết quả tìm kiếm']);
    }
}
