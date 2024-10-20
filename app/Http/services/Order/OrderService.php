<?php

namespace App\Http\services\Order;
use App\Models\Customers;
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
}