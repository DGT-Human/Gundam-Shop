<?php

namespace App\Http\services\Product;

use App\Models\Menu;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductAdminService
{
    public function getMenu()
    {
        return Menu::where('active', 1)->get();
    }

    protected function isValidPrice($request)
    {
        if ($request->input('price') != 0 && $request->input('price_sale') != 0 && $request->input('price_sale') >= $request->input('price')) {
            Session::flash('error', 'Giá khuyến mãi phải nhỏ hơn giá gốc');
            return false;
        }

        if($request->input('price') ==  0 && $request->input('price_sale') != 0){
            Session::flash('error', 'Giá gốc không được để trống');
            return false;
        }
            return true;
    }

    public function insert($request){
        $isValidPrice = $this->isValidPrice($request);
        if($isValidPrice === false){
            return false;
        }
        // con cach nay cho toan bo
        try{
            $request -> except('_token');
            Product::Create($request->all());
            Session::flash('success', 'Thêm mới thành công');
        }catch (\Exception $e){
            Session::flash('error', 'Thêm mới thất bại');
            log::info($e->getMessage());
            return false;
        }
    }

    public function get()
    {
        return Product::with('menu')->orderByDesc('id')->paginate(15);
    }

    public function update( $request, $products)
    {
        $isValidPrice = $this->isValidPrice($request);
        if ($isValidPrice === false) {
            return false;
        }
        try {
            $products->update($request->all());
            Session::flash('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            Session::flash('error', 'Cập nhật thất bại');
            log::info($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
       $product = Product::where ('id', $request->input('id'))->first();
       if($product){
           $product->delete();
           return true;

         }else {
           return false;
       }
    }
}