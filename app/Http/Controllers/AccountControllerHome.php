<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\services\user\AccountService;

class AccountControllerHome extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('account.myaccount', ['title' => 'Thông tin cá nhân']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

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
        $this->accountService->update($request, $id);
        return redirect()->back();
    }

    public function orders(string $id)
    {
        $orders = $this->accountService->getOrders($id);
        foreach ($orders as $order) {
            $productId = $order->product_id; // Lấy product_id từ từng order
            $product = $this->accountService->getProduct($productId);
            // Xử lý logic với product
        }
        return view('account.orders', ['title' => 'Đơn hàng của tôi'
            , 'orders' => $orders, 'products' => $product
        ]);
    }
}

