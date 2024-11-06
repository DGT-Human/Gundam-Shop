<?php

namespace App\Http\services\user;

use App\Models\User;
use App\Models\Customers;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $user->city = $request->input('city');
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
                'date' => $group->first()->created_at->format('Y-m-d H:i:s'),
                'price' => $group->first()->price_sale > 0
                    ? $group->first()->price_sale * $group->first()->quantity
                    : $group->first()->price * $group->first()->quantity,
                'total_price' => $group->sum(function ($cart) {
                    return $cart->price_sale > 0
                        ? $cart->price_sale * $cart->quantity
                        : $cart->price * $cart->quantity;
                }),  // Tính tổng tiền cho mỗi nhóm
                'orders' => $group, // Danh sách đơn hàng trong nhóm
                'sum_quantity' => $group->sum('quantity'),
                'status' => $group->first()->status
            ];
        });

        $sortedCarts = $groupedCarts->sortByDesc('date')->values();

        // Paginate the sorted result using LengthAwarePaginator
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 7;
        $currentItems = $sortedCarts->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedCarts = new LengthAwarePaginator(
            $currentItems,
            $sortedCarts->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $paginatedCarts;
    }

    public function getOrder($id, $date)
    {
        // Tìm đối tượng Cart với ID tương ứng
        $cart = Cart::find($id);

        // Nếu không tìm thấy, trả về mảng rỗng
        if (!$cart) {
            return [];
        }

        // Lấy tất cả các đơn hàng có cùng ngày tạo
        $sameDateCarts = Cart::where('created_at', $date)->where('customer_id', $cart->customer_id)->get();

        $sameDateCarts = $sameDateCarts->map(function ($cart) {
            $totalPrice = $cart->price_sale > 0
                ? $cart->price_sale * $cart->quantity
                : $cart->price * $cart->quantity;

            // Chuyển mỗi đối tượng Cart thành mảng và thêm `total_price`
            return array_merge($cart->toArray(), ['total_price' => $totalPrice]);
        });


        // Nếu có nhiều đơn hàng cùng ngày, trả về danh sách
        if ($sameDateCarts->count() > 1) {
            return $sameDateCarts;
        }
//        $cart = $cart->toArray();
        // Nếu chỉ có một đơn hàng, trả về đơn hàng đó
        return $sameDateCarts->first();
    }


//    public function getProduct ($id)
//    {
//        $product = Product::find($id);
//        return $product;
//    }

    public function getCustomer($id)
    {
        $customer = Customers::where('id', $id)->get();
        return $customer;
    }

    public function cancel($id, $date)
    {
        $carts = Cart::find($id);
        $sameDateCarts = Cart::where('created_at', $date)->where('customer_id', $carts->customer_id)->get();
        foreach ($sameDateCarts as $cart) {
            $cart->status = 'canceled';
            $cart->save();
            $product = Product::find($cart->product_id);
            $product->quantity += $cart->quantity;
            $product->save();
        }
        Session()->flash('success', 'Hủy đơn hàng thành công');
    }

    public function changePassword($request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return false;
        }
        if (!bcrypt($request->input('old_password')) === $user->password) {
            session()->flash('error', 'Mật khẩu cũ không đúng');
            return false;

        }
        if ($request->input('new_password') != $request->input('password_confirmation')) {
            session()->flash('error', 'Xác nhận mật khẩu không đúng');
            return false;
        }
        $user->password = bcrypt($request->input('new_password'));
        $user->save();
        session()->flash('success', 'Đổi mật khẩu thành công');
        return true;
    }
}