<?php

namespace App\Http\services\user;

use App\Models\User;
use App\Models\Customers;
use App\Models\Cart;
use App\Models\Product;

class AccountService
{
    public function update($request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return false;
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();
        return true;
    }

    public function getOrders($id)
    {
        $user = User::find($id);
        $customer = Customers::where('email', $user->email)->first();
        if(!$customer){
            return [];
        }
        $Cart = Cart::where('customer_id', $customer->id)->get();
        if(!$Cart){
            return [];
        }

        $groupedCarts = $Cart->groupBy(function ($cart) {
            return $cart->created_at->format('d/m/Y H:i:s'); // Nhóm các đơn hàng theo ngày
        })->map(function ($group) {
            return [
                'id' => $group->first()->id,
                'date' => $group->first()->created_at->format('d/m/Y H:i:s'),
                'price' => $group->first()->price * $group->first()->quantity,
                'total_price' => $group->sum(function ($cart) {
                    return $cart->price * $cart->quantity; // Tổng giá trị cho mỗi nhóm đơn hàng
                }), // Tính tổng tiền cho mỗi nhóm
                'orders' => $group, // Danh sách đơn hàng trong nhóm
                'sum_quantity' => $group->sum('quantity'),
                'status' => $group->first()->status
            ];
        });

        return $groupedCarts;
    }

    public function getOrder($id)
    {
        // Tìm đối tượng Cart với ID tương ứng
        $cart = Cart::find($id);

        // Nếu không tìm thấy, trả về mảng rỗng
        if (!$cart) {
            return [];
        }

        // Lấy tất cả các đơn hàng có cùng ngày tạo
        $sameDateCarts = Cart::where('created_at', $cart->created_at)->where('customer_id', $cart->customer_id)->get();

        // Nếu có nhiều đơn hàng cùng ngày, trả về danh sách
        if ($sameDateCarts->count() > 1) {
            return $sameDateCarts;
        }
        $cart = $cart->toArray();
        // Nếu chỉ có một đơn hàng, trả về đơn hàng đó
        return $cart;
    }


    public function getProduct ($id)
    {
        $product = Product::find($id);
        return $product;
    }
}