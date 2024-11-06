<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\services\user\AccountService;
use App\Http\services\Product\ProductService;

class AccountControllerHome extends Controller
{
    protected $accountService;
    protected $productService;

    public function __construct(AccountService $accountService, ProductService $productService)
    {
        $this->accountService = $accountService;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        $orders = $this->accountService->getOrders($id);
//        dd($orders);
        return view('account.myaccount', [
            'title' => 'Thông tin cá nhân',
            'groups' => $orders,
            'productCarts' => $this->productService->getAll()
        ]);
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
    public function setting()
    {
        return view('account.setting', [
            'title' => 'Cài đặt tài khoản',
            'productCarts' => $this->productService->getAll()
        ]);
    }
    public function update(Request $request, string $id)
    {
        $this->accountService->update($request, $id);
        return redirect()->back();
    }

    public function changePassword(Request $request, $id){
        $this->accountService->changePassword($request, $id);
        return redirect()->back();
    }

    public function order(string $id, string $date)
    {
        $orders = $this->accountService->getOrder($id, $date);
        if (count($orders) === 9){
            $id1 = $orders['customer_id'];
            $customer = $this->accountService->getCustomer($id1);

            return view('account.orderDetail', [
                'title' => 'Đơn hàng của tôi',
                'orders' => $orders,
                'customer' => $customer,
                'productCarts' => $this->productService->getAll()
            ]);
        }
        else{
        $id1 = $orders[0]['customer_id'];
        $customer = $this->accountService->getCustomer($id1);

        return view('account.orderDetail', [
            'title' => 'Đơn hàng của tôi',
            'orders' => $orders,
            'customer' => $customer,
            'productCarts' => $this->productService->getAll()
        ]);
        }
    }

    public function cancel(string $id, string $date)
    {
        $this->accountService->cancel($id, $date);
        return redirect()->back();
    }
}

