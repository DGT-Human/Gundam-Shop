<?php

namespace App\Http\services;

use App\Jobs\SendMail;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Customers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function create($request){
        $qty = (int) $request->input('num-product');
        $product_id = (int) $request->input('product_id');
        $quantity = Product::find($product_id)->quantity;
        if($qty <= 0 || $product_id <= 0 || $qty > $quantity){
            Session::flash('error', 'Số lượng sản phẩm không hợp lệ');
            return false;
        }

        $carts = Session::get('carts');
        if (is_null($carts)) {
            Session::put('carts', [
                $product_id=> $qty
            ]);
            return true;
        }
        $exists = Arr::exists($carts, $product_id);
        if($exists){
            if ($carts[$product_id] + $qty > $quantity){
                Session::flash('error', 'Số lượng sản phẩm không hợp lệ');
                return false;
            }
            else {
                $carts[$product_id] = $carts[$product_id] + $qty;
                Session::put('carts',
                    $carts
                );
                return true;
            }
        }

        $carts[$product_id] = $qty;
        Session::put('carts',
            $carts
        );
        return true;
    }

    public function getProduct(){
        $carts = Session::get('carts');
        if(is_null($carts)){
            return [];
        }
        $product_ids = array_keys($carts);
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->whereIn('id', $product_ids)
            ->where('active', 1)
            ->get();
    }
    public function update($request){
       Session::put('carts', $request->input('num-product'));
       return true;
    }
    public function destroy($id){
        $carts = Session::get('carts');
        if(is_null($carts)){
            return false;
        }
        unset($carts[$id]);
        Session::put('carts', $carts);
        return true;
    }

    public function addCarts($request){
        try{
            DB::beginTransaction(); //kiem tra xem co loi hay khong
            $carts = Session::get('carts');
            if(is_null($carts)){
                return false;
            }
            $customer = Customers::where('email', $request->input('email'))->first();
            if($customer){
                $this->infoProductCart($carts, $customer);
                DB::commit();
                Session::flash('success', 'Đặt hàng thành công');
                SendMail::dispatch($request->input('email'))->delay(now()->addSeconds(5));
                Session::forget('carts');
                return true;
            }
            $customer = Customers::Create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'content' => $request->input('content')
            ]);
            $this->infoProductCart($carts, $customer);
            DB::commit();
           Session::flash('success', 'Đặt hàng thành công');
           SendMail::dispatch($request->input('email'))->delay(now()->addSeconds(5));
           Session::forget('carts');
           return true;
        }catch (\Exception $e){
            DB::rollBack();
            log::info($e->getMessage());
            Session::flash('error', 'Đặt hàng thất bại');
            return false;
        }
    }

    protected function infoProductCart($carts, $customer){
        $product_ids = array_keys($carts);
        $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb', 'quantity')
            ->whereIn('id', $product_ids)
            ->where('active', 1)
            ->get();
        $data = [];
        foreach ($products as $product){
            $quantity_in_cart = $carts[$product->id];

            // Kiểm tra nếu số lượng trong giỏ hàng không vượt quá số lượng còn lại của sản phẩm
            if ($quantity_in_cart > $product->quantity) {
                // Đưa ra thông báo lỗi hoặc xử lý trường hợp không đủ số lượng
                throw new \Exception("Sản phẩm {$product->name} không đủ số lượng.");
            }
            $data[] = [
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'quantity' => $carts[$product->id],
                'price' => $product->price_sale !== 0 ? $product->price_sale : $product->price,
            ];
            $product->quantity -= $quantity_in_cart;
            $product->save();
        }

        return Cart::insert($data);
    }
}