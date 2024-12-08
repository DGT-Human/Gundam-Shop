<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\services\Order\OrderService;

class OrderController extends Controller
{

    protected $orderService;
    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }

    public function index()
    {
        return view('admin.order.list',[
            'title' => 'Danh sách đơn hàng',
            'orders' => $this->orderService->getAll()
        ]);
    }

    public function show($id, $date)
    {
        $order = $this->orderService->getOrder($id, $date);
        $products = $this->orderService->getProducts($id, $date);
        return view('admin.order.detail',[
            'title' => 'Chi tiết đơn hàng',
            'order' => $order,
            'products' => $products
        ]);
    }

    public function shipping($id, $date)
    {
        $this->orderService->submit($id, $date);
        return redirect()->back();
    }

    public function cancel($id, $date)
    {
        $this->orderService->cancel($id, $date);
        return redirect()->back();
    }

    public function complete($id, $date)
    {
        $this->orderService->complete($id, $date);
        return redirect()->back();
    }

}
