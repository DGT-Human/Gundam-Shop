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
        if ($keyword === null || $keyword === '' || !preg_match('/^[a-zA-Z0-9\s]+$/', $keyword)) {
            return redirect()->route('home')->with('error', 'Từ khóa tìm kiếm không hợp lệ. Vui lòng chỉ nhập chữ cái hoặc số.');
        }
        $menus = $this->menuService->search($keyword);
        $productCarts = $this->productService->getAll();
        $products = $this->productService->search($keyword);
        if(count($products) == 0){
            return view('menu', ['products' => $products, 'menus' =>  $menus, 'productCarts' => $productCarts, 'title' => 'Không tìm thấy kết quả']);
        }
        return view('menu', ['products' => $products, 'menus' =>  $menus, 'productCarts' => $productCarts, 'title' => 'Kết quả tìm kiếm']);
    }

    public function searchMoney (Request $request){
        $url = $request->input('category_id');
        $url = explode('/', $url);
        $url = end($url);
        $url = explode('-', $url);
        $id = $url[0];
        $parent_id = $url[1];
        $menus = $this->menuService->show();
        $productCarts = $this->productService->getAll();
        $products = $this->productService->searchMoney($request->input('min_price'), $request->input('max_price'), $id, $parent_id);
        if(count($products) == 0){
            return view('menu', ['products' => $products, 'menus' =>  $menus, 'productCarts' => $productCarts, 'title' => 'Không tìm thấy kết quả']);
        }
        return view('menu', ['products' => $products, 'menus' =>  $menus, 'productCarts' => $productCarts, 'title' => 'Kết quả tìm kiếm']);
    }
}
