<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\Product\ProductService;

class WishlistController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    // Hiển thị danh sách Wishlist
    public function index()
    {
        $productCarts = $this->productService->getAll();
        $wishlists = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('wishlist', [
            'title' => 'Wishlist',
            'wishlists' => $wishlists,
            'productCarts' => $productCarts
        ]);
    }

    // Thêm sản phẩm vào Wishlist
    public function store(Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return redirect()->back()->with('success', 'Product added to wishlist!');
    }

    // Xóa sản phẩm khỏi Wishlist
    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->delete();
        return redirect()->back()->with('success', 'Product removed from wishlist!');
    }
}

