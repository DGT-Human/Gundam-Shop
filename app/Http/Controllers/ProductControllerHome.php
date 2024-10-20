<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\services\Product\ProductService;
use Illuminate\Support\Facades\Session;
use App\Http\services\CartService;

class ProductControllerHome extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService, CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
    }
    public function index($id = '', $slug = '')
    {
        $product = $this->productService->getProduct($id);
        $productCarts = $this->cartService->getProduct();
        $productMore = $this->productService->more($id);
        $productCarts = $this->productService->getAll();
        return view('product.content', [
            'title' => $product->name,
            'product' => $product,
            'cart' => Session::get('carts'),
            'products' => $productMore,
            'productCarts' => $productCarts
        ]);
    }


}
