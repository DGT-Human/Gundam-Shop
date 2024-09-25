<?php

namespace App\Http\services\Order;
use App\Models\Customers;
class OrderService
{
    public function getAll()
    {
        return Customers::orderbyDesc('id')->paginate(20);
    }
}