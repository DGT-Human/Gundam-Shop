<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\services\CartService;
use Illuminate\Support\Facades\Session;
use App\Http\services\Product\ProductService;
class CartController extends Controller
{
    protected $cartService;
    protected $productService;

    public function __construct(CartService $cartService, ProductService $productService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $result = $this->cartService->create($request);
        if($result === false){
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function show(){
        $product = $this -> cartService->getProduct();
        $productAll = $this -> productService->getAll();
        return view('carts.list',[
            'title' => 'Giỏ hàng',
            'products' => $product,
            'carts' => Session::get('carts'),
            'productCarts' => $productAll
        ]);
    }

    public function update(Request $request){
        $result = $this->cartService->update($request);
        return redirect('/carts');
    }

    public function remove($id){
        $result = $this->cartService->destroy($id);
        return redirect()->back();
    }

    public function addCarts(Request $request){
        $result = $this->cartService->addCarts($request);
        return redirect('/carts');
    }
}
