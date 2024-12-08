<?php

namespace App\Http\services\Order;
use App\Models\Customers;
use App\Models\Product;
use App\Models\cart;
class OrderService
{
    public function getAll()
    {
        return Cart::selectRaw('c.id as customer_id, c.name as customer_name, c.address as customer_address, c.phone as customer_phone, c.email as customer_email, carts.created_at, sum(price*quantity) as total, carts.status')
            ->join('customers as c', 'c.id', '=', 'carts.customer_id')
            ->groupBy('c.id', 'c.name', 'c.email', 'carts.created_at', 'c.phone' , 'c.address', 'carts.status')
            ->orderByDesc('carts.created_at')
            ->orderByDesc('c.id')
            ->paginate(20);
    }

    public function getOrder($id, $date)
    {
        $order = Cart::selectRaw('c.id as customer_id, c.content as customer_content, c.name as customer_name, c.address as customer_address, c.phone as customer_phone, c.email as customer_email, carts.created_at, sum(price*quantity) as total, carts.status')
            ->join('customers as c', 'c.id', '=', 'carts.customer_id')
            ->where('carts.customer_id', $id)
            ->where('carts.created_at', $date)
            ->groupBy('c.id', 'c.name', 'c.email', 'c.content', 'carts.created_at', 'c.phone', 'c.address', 'carts.status')
            ->orderByDesc('carts.created_at')
            ->orderByDesc('c.id')
            ->first();

        // Kiểm tra nếu $order không null
        return $order ? $order->toArray() : null;
    }

    public function getProducts($id, $date)
    {
        return Cart::selectRaw('products.id, products.name, products.price, products.price_sale, carts.quantity, carts.price as total')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->where('carts.customer_id', $id)
            ->where('carts.created_at', $date)
            ->get();
    }

    public function submit($id, $date)
    {
        $orders = Cart::where('customer_id', $id)
            ->where('created_at', $date)->get();
        foreach ($orders as $order) {
            $order->status = 'shipping';
            $order->save();
        }
        Session()->flash('success', 'Cập nhật đơn hàng thành công');
    }

    public function cancel($id, $date)
    {
        $orders = Cart::where('customer_id', $id)
            ->where('created_at', $date)
            ->get();
        foreach ($orders as $order) {
            $order->status = 'canceled';
            $order->save();
            $product = Product::find($order->product_id);
            $product->quantity += $order->quantity;
            $product->save();
        }
        Session()->flash('success', 'Hủy đơn hàng thành công');
    }

    public function complete($id, $date)
    {
        $orders = Cart::where('customer_id', $id)
            ->where('created_at', $date)
            ->get();
        foreach ($orders as $order) {
            $order->status = 'completed';
            $order->save();
        }
        Session()->flash('success', 'Hoàn thành đơn hàng thành công');
    }
}