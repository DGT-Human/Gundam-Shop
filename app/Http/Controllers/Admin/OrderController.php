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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.order.list',[
            'title' => 'Danh sách đơn hàng',
            'orders' => $this->orderService->getAll()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
