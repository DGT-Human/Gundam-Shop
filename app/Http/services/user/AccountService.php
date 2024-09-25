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
        return $Cart;
    }

    public function getProduct ($id)
    {
        $product = Product::find($id);
        return $product;
    }
}