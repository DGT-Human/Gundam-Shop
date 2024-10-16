<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\services\Product\ProductService;

class ProductControllerHome extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index($id = '', $slug = '')
    {
        $product = $this->productService->getProduct($id);
        $productMore = $this->productService->more($id);
        $productCarts = $this->productService->getAll();
        return view('product.content', [
            'title' => $product->name,
            'product' => $product,
            'products' => $productMore,
            'productCarts' => $productCarts
        ]);
    }


}
